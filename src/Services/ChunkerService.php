<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Models\ExcelRow;
use Akbarjimi\ExcelImporter\Models\ExcelRowChunk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ChunkerService
{
    public function __construct(private readonly int $chunkSize = 1000) {}

    /**
     * Atomically creates ALL chunks for ALL sheets in a file and marks them dispatchable.
     * Dispatch should happen AFTER COMMIT (jobs use ->afterCommit()).
     */
    public function createChunksForFile(ExcelFile $file): Collection
    {
        return DB::transaction(function () use ($file) {
            $all = collect();

            foreach ($file->excelSheets as $sheet) {
                $ids = ExcelRow::query()
                    ->where('excel_sheet_id', $sheet->getKey())
                    ->orderBy('id')
                    ->pluck('id');

                if ($ids->isEmpty()) {
                    continue;
                }

                $chunks = $ids->chunk($this->chunkSize)->map(function ($idChunk) use ($sheet) {
                    return ExcelRowChunk::create([
                        'excel_sheet_id' => $sheet->getKey(),
                        'from_row_id'    => $idChunk->first(),
                        'to_row_id'      => $idChunk->last(),
                        'size'           => $idChunk->count(),
                        'status'         => 'pending',
                    ]);
                });

                $all = $all->merge($chunks);
            }

            Log::info('Chunks created', [
                'file_id'   => $file->getKey(),
                'chunk_cnt' => $all->count(),
                'chunk_sz'  => $this->chunkSize,
            ]);

            return $all;
        }, 3);
    }
}
