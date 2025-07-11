<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Services\RowExtractionService;

class HandleSheetsDiscovered
{
    public function __construct(protected RowExtractionService $service)
    {
    }

    public function handle(SheetsDiscovered $event): void
    {
        foreach ($event->sheets as $sheet) {
            $this->service->extract($sheet);
        }
    }
}
