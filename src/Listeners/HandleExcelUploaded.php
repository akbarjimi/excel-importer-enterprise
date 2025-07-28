<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Events\SheetDiscovered;
use Akbarjimi\ExcelImporter\Repositories\ExcelSheetRepository;
use Akbarjimi\ExcelImporter\Services\SheetDiscoveryService;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Listener: Discover sheets as soon as the file is registered.
 *
 * Flow:
 *   ExcelUploaded  ─▶  HandleExcelUploaded  ─▶  SheetsDiscovered
 */
final readonly class HandleExcelUploaded
{
    public function __construct(
        private SheetDiscoveryService $discovery,
        private ExcelSheetRepository $sheetRepo,
        private Dispatcher $events,
    ) {}

    public function handle(ExcelUploaded $event): void
    {
        // 1. Discover sheets through adapter‑powered service
        $sheetDTOs = $this->discovery->discover($event->file);

        // 2. Persist sheet metadata via repository (avoids ActiveRecord in listener)
        $this->sheetRepo->bulkCreate($event->file->id, $sheetDTOs);

        // 3. Dispatch next pipeline event
        $this->events->dispatch(new SheetDiscovered($event->file->id));
    }
}
