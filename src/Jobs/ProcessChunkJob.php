<?php

namespace Akbarjimi\ExcelImporter\Jobs;

use Akbarjimi\ExcelImporter\Models\ExcelRow;
use Akbarjimi\ExcelImporter\Models\ExcelRowChunk;
use Akbarjimi\ExcelImporter\Services\PersistService;
use Akbarjimi\ExcelImporter\Services\TransformService;
use Akbarjimi\ExcelImporter\Services\ValidateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ProcessChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public int $timeout = 120;

    public function __construct(public readonly int $chunkId)
    {
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping("chunk:{$this->chunkId}"))->dontRelease()];
    }

    public function handle(
        TransformService $transform,
        ValidateService  $validate,
        PersistService   $persist
    ): void
    {
        /** @var ExcelRowChunk $chunk */
        $chunk = ExcelRowChunk::findOrFail($this->chunkId);

        if ($chunk->status === 'completed') {
            return; // idempotent
        }

        $chunk->update(['status' => 'processing']);
        $rows = ExcelRow::query()
            ->where('excel_sheet_id', $chunk->excel_sheet_id)
            ->whereBetween('id', [$chunk->from_row_id, $chunk->to_row_id])
            ->orderBy('id')
            ->cursor();

        $processed = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $data = $transform->apply($row);
                $validate->apply($data, $row);
                $persist->store($data, $row);
                $processed++;
            }

            $chunk->update([
                'status' => 'completed',
                'attempts' => $chunk->attempts + 1,
                'processed_at' => now(),
                'error' => null,
            ]);

            DB::commit();
            Log::info('Chunk processed', ['chunk_id' => $chunk->getKey(), 'processed' => $processed]);
        } catch (\Throwable $e) {
            DB::rollBack();

            $chunk->update([
                'status' => 'failed',
                'attempts' => $chunk->attempts + 1,
                'error' => substr($e->getMessage(), 0, 2000),
            ]);

            Log::error('Chunk processing failed', [
                'chunk_id' => $chunk->getKey(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
