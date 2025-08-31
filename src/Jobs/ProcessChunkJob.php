<?php

namespace Akbarjimi\ExcelImporter\Jobs;

use Akbarjimi\ExcelImporter\Models\ExcelRow;
use Akbarjimi\ExcelImporter\Models\ExcelRowChunk;
use Akbarjimi\ExcelImporter\Repositories\ExcelRowRepository;
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

    /**
     * @param TransformService $transform transforms a single ExcelRow -> array
     * @param ValidateService $validate validates transformed data (throws on invalid)
     * @param ExcelRowRepository $repo low-level persistence (bulk/row ops)
     */
    public function handle(
        TransformService   $transform,
        ValidateService    $validate,
        ExcelRowRepository $repo
    ): void
    {
        /** @var ExcelRowChunk $chunk */
        $chunk = ExcelRowChunk::findOrFail($this->chunkId);

        if ($chunk->status === 'completed') {
            return;
        }

        $chunk->update(['status' => 'processing', 'attempts' => $chunk->attempts + 1]);

        $rowsCursor = ExcelRow::query()
            ->where('excel_sheet_id', $chunk->excel_sheet_id)
            ->whereBetween('id', [$chunk->from_row_id, $chunk->to_row_id])
            ->orderBy('id')
            ->cursor();

        $outgoingRows = [];
        $batchSize = 200;
        $processedCount = 0;

        DB::beginTransaction();
        try {
            /** @var ExcelRow $row */
            foreach ($rowsCursor as $row) {
                $payload = $transform->apply($row->toArray());
                $validate->apply($payload);

                $outgoingRows[] = [
                    'excel_sheet_id' => $row->excel_sheet_id,
                    'row_index' => $row->row_index,
                    'content' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $processedCount++;

                if (count($outgoingRows) >= $batchSize) {
                    $repo->bulkUpsert($outgoingRows);
                    $outgoingRows = [];
                }
            }

            if (!empty($outgoingRows)) {
                $repo->bulkUpsert($outgoingRows);
            }

            $chunk->update([
                'status' => 'completed',
                'processed_at' => now(),
                'error' => null,
            ]);

            DB::commit();

            Log::info('Chunk processed', [
                'chunk_id' => $chunk->getKey(),
                'rows' => $processedCount,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            $chunk->update([
                'status' => 'failed',
                'error' => mb_substr($e->getMessage(), 0, 2000),
            ]);

            Log::error('Chunk processing failed', [
                'chunk_id' => $chunk->getKey(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
