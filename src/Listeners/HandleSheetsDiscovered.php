<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\SheetDiscovered;
use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Repositories\ExcelSheetRepository;

final readonly class HandleSheetsDiscovered
{
    public function __construct(
        private ExcelSheetRepository $sheetRepo,
    ) {}

    public function handle(SheetsDiscovered $event): void
    {
        $sheets = $this->sheetRepo->getByFileId($event->fileId);

        foreach ($sheets as $sheet) {
            event(new SheetDiscovered($sheet));
        }
    }
}
