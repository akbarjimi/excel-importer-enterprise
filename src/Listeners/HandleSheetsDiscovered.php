<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\AllSheetsDispatched;
use Akbarjimi\ExcelImporter\Events\SheetDiscovered;
use Akbarjimi\ExcelImporter\Repositories\ExcelSheetRepository;

final readonly class HandleSheetsDiscovered
{
    public function __construct(
        private ExcelSheetRepository $sheetRepo,
    )
    {
    }

    public function handle(SheetDiscovered $event): void
    {
        $sheets = $this->sheetRepo->getByFileId($event->fileId);
        $lastIndex = $sheets->count() - 1;

        foreach ($sheets as $i => $sheet) {
            event(new SheetDiscovered($sheet, $i === $lastIndex));
        }

        event(new AllSheetsDispatched($sheets->first()->file));
    }
}