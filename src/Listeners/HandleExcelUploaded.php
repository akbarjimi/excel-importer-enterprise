<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Services\SheetDiscoveryService;
use Illuminate\Support\Facades\Event;

class HandleExcelUploaded
{
    public function handle(ExcelUploaded $event): void
    {
        $sheets = (new SheetDiscoveryService())->discover($event->file);

        Event::dispatch(new SheetsDiscovered(collect($sheets)));
    }
}
