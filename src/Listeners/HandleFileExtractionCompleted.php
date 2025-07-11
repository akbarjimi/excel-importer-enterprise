<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\FileExtractionCompleted;
use Akbarjimi\ExcelImporter\Events\ProcessRowsChunk;
use Akbarjimi\ExcelImporter\Services\ChunkService;
use Illuminate\Contracts\Events\Dispatcher;

class HandleFileExtractionCompleted
{
    public function __construct(
        protected ChunkService $chunking,
        protected Dispatcher   $events
    )
    {
    }

    public function handle(FileExtractionCompleted $event): void
    {
        foreach ($event->file->sheets as $sheet) {
            $chunks = $this->chunking->splitSheetIntoChunks($sheet);

            foreach ($chunks as $index => $chunkMeta) {
                $this->events->dispatch(new ProcessRowsChunk(
                    sheetId: $sheet->id,
                    chunkIndex: $index
                ));
            }
        }
    }
}
