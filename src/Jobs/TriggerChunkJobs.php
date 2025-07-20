<?php

namespace Akbarjimi\ExcelImporter\Jobs;

use Akbarjimi\ExcelImporter\Events\RowsExtracted;
use Akbarjimi\ExcelImporter\Repositories\ExcelFileRepository;
use Akbarjimi\ExcelImporter\Services\ChunkService;

class TriggerChunkJobs
{
    public function __construct(
        private ChunkService        $chunkService,
        private ExcelFileRepository $sheetRepo,
        private ExcelFileRepository $fileRepo,
    )
    {
    }

    public function handle(RowsExtracted $event): void
    {
        $sheetId = $event->sheet->id;
        $fileId = $event->sheet->excel_file_id;

        $this->sheetRepo->markExtracted($sheetId);

        if ($this->sheetRepo->allExtracted($fileId)) {
            $this->fileRepo->markAsReadyForProcessing($fileId);

            $sheets = $this->sheetRepo->getByFileId($fileId);

            foreach ($sheets as $sheet) {
                $this->chunkService->splitSheetIntoChunks($sheet);
            }
        }
    }
}