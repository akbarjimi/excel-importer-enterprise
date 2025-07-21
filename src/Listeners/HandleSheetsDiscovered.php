<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Repositories\ExcelSheetRepository;
use Akbarjimi\ExcelImporter\Services\RowExtractionService;

/**
 * Listener: Kick off row extraction for each discovered sheet.
 *
 * Flow:
 *   SheetsDiscovered  ─▶  HandleSheetsDiscovered  ─▶  RowsExtracted (per sheet)
 */
final readonly class HandleSheetsDiscovered
{
    public function __construct(
        private RowExtractionService $extractor,
        private ExcelSheetRepository $sheetRepo,
    ) {}

    public function handle(SheetsDiscovered $event): void
    {
        // Pull fresh sheet models through the repo
        $sheets = $this->sheetRepo->getByFileId($event->fileId);

        foreach ($sheets as $sheet) {
            // RowExtractionService fires RowsExtracted internally
            $this->extractor->extract($sheet);
        }
    }
}
