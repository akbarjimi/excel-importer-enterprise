<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\AllSheetsDispatched;
use Akbarjimi\ExcelImporter\Events\SheetDiscovered;
use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Repositories\ExcelSheetRepository;
use Akbarjimi\ExcelImporter\Services\SheetDiscoveryService;

final readonly class HandleSheetsDiscovered
{
    public function __construct(
        private ExcelSheetRepository  $sheetRepo,
        private SheetDiscoveryService $discovery,
        private Dispatcher            $events,
    )
    {
    }

    public function handle(SheetsDiscovered $event): void
    {
        $sheets = $this->sheetRepo->getByFileId($event->fileId);
        $lastIndex = $sheets->count() - 1;

        foreach ($sheets as $i => $sheet) {
            event(new SheetDiscovered($sheet, $i === $lastIndex));
        }

        event(new AllSheetsDispatched($event->fileId));
    }
}