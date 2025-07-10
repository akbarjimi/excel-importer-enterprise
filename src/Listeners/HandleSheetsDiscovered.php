<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Services\RowExtractionService;

class HandleSheetsDiscovered
{
    public function handle(SheetsDiscovered $event): void
    {
        $service = new RowExtractionService();

        foreach ($event->sheets as $sheet) {
            $service->extract($sheet);
        }
    }
}
