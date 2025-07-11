<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Events\ProcessRowsChunk;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Contracts\Events\Dispatcher;

class ChunkService
{
    public function __construct(private Dispatcher $events)
    {
    }

    public function splitSheetIntoChunks(ExcelSheet $sheet): void
    {
        $chunkSize = config('excel-importer.chunk_size', 1000);

        $totalRows = $sheet->rows()->count();
        $chunks = (int)ceil($totalRows / $chunkSize);

        for ($i = 0; $i < $chunks; $i++) {
            $this->events->dispatch(new ProcessRowsChunk(sheetId: $sheet->id, chunkIndex: $i));
        }
    }
}
