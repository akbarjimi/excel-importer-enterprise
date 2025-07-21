<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Events\ProcessRowsChunk;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class ChunkService
{
    public function __construct(private readonly Dispatcher $events) {}

    public function splitSheetIntoChunks(ExcelSheet $sheet): void
    {
        $chunkSize = config('excel-importer.chunk_size', 1000);

        $totalRows = $sheet->rows()->count();
        if ($totalRows === 0) {
            Log::info("Sheet [{$sheet->id}] has no rows to process.");

            return;
        }

        $chunkCount = (int) ceil($totalRows / $chunkSize);

        for ($i = 0; $i < $chunkCount; $i++) {
            $this->events->dispatch(
                new ProcessRowsChunk(
                    sheetId: $sheet->id,
                    chunkIndex: $i,
                )
            );
        }

        Log::info("Dispatched {$chunkCount} chunk jobs for sheet [{$sheet->id}].");
    }
}
