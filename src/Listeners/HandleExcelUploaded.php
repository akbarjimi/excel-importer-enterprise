<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Services\SheetDiscoveryService;
use Illuminate\Support\Facades\Event;

class HandleExcelUploaded
{
    public function __construct(
        protected SheetDiscoveryService $service,
        protected Dispatcher            $events
    )
    {
    }

    public function handle(ExcelUploaded $event): void
    {
        $sheets = $this->service->discover($event->file);
        $this->events->dispatch(new SheetsDiscovered(collect($sheets)));
    }
}