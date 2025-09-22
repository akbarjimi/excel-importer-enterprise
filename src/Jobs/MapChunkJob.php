<?php

namespace Akbarjimi\ExcelImporter\Jobs;

use Akbarjimi\ExcelImporter\Enums\ExcelRowStatus;
use Akbarjimi\ExcelImporter\Models\ExcelRow;
use Akbarjimi\ExcelImporter\Models\ExcelRowChunk;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Repositories\ExcelRowRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

final class MapChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public readonly int $chunkId)
    {
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping("map-chunk:{$this->chunkId}"))->dontRelease()];
    }

    public function handle(ExcelRowRepository $repo): void
    {
        /** @var ExcelRowChunk $chunk */
        $chunk = ExcelRowChunk::findOrFail($this->chunkId);
        $sheet = ExcelSheet::findOrFail($chunk->excel_sheet_id);

        if ($chunk->mapping_status === 'completed') {
            return;
        }

        $chunk->update(['mapping_status' => 'processing']);

        $rowsCursor = ExcelRow::query()
            ->where('excel_sheet_id', $chunk->excel_sheet_id)
            ->whereBetween('id', [$chunk->from_row_id, $chunk->to_row_id])
            ->whereNull('mapped_at')
            ->orderBy('id')
            ->cursor();

        $mapperConfig = Config::get("excel-importer-sheets.{$sheet->name}.mapper");
        $mapperBatchSize = Config::get('excel-importer.mapper_batch_size');

        $batch = [];
        $rowIds = [];
        $mappedTotal = 0;

        DB::beginTransaction();
        try {
            foreach ($rowsCursor as $row) {
                $payload = is_array($row->content) ? $row->content : json_decode($row->content, true);

                $batch[] = ['row' => $row, 'payload' => $payload];

                if (count($batch) >= $mapperBatchSize) {
                    $this->processBatch($batch, $repo, $mapperConfig, $rowIds, $mappedTotal, $sheet);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                $this->processBatch($batch, $repo, $mapperConfig, $rowIds, $mappedTotal, $sheet);
            }

            $chunk->update(['mapping_status' => 'completed', 'mapped_at' => now()]);

            if ($mappedTotal > 0) {
                DB::table('excel_sheets')->where('id', $sheet->id)->increment('mapped_count', $mappedTotal);
            }

            $sheetFresh = ExcelSheet::find($sheet->id);
            if (!is_null($sheetFresh->rows_count) && $sheetFresh->mapped_count >= $sheetFresh->rows_count) {
                $sheetFresh->update(['mapped_at' => now()]);
            }

            DB::commit();

            Log::info('MapChunkJob completed', [
                'chunk_id' => $chunk->id,
                'mapped' => $mappedTotal,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            $chunk->update(['mapping_status' => 'failed']);

            Log::error('MapChunkJob failed', [
                'chunk_id' => $chunk->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function processBatch(array $batch, ExcelRowRepository $repo, $mapperConfig, array &$rowIds, int &$mappedTotal, ExcelSheet $sheet): void
    {
        $payloads = array_map(fn($item) => $item['payload'], $batch);
        $rows = array_map(fn($item) => $item['row'], $batch);

        $mapped = [];
        if (is_callable($mapperConfig)) {
            $mapped = call_user_func($mapperConfig, $payloads);
        } else {
            $mapped = $payloads;
        }

        if (!is_array($mapped)) {
            throw new RuntimeException('Mapper must return an array of mapped rows.');
        }

        $targetModel = config("excel-importer-sheets.{$sheet->name}.target_model");
        $repo->bulkInsertDomain($targetModel, $mapped);

        $ids = array_map(fn($r) => $r->id, $rows);
        ExcelRow::whereIn('id', $ids)->update([
            'status' => ExcelRowStatus::PROCESSED->value,
            'mapped_at' => now(),
        ]);

        $rowIds = array_merge($rowIds, $ids);
        $mappedTotal += count($mapped);
    }
}
