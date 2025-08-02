<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Repositories\ExcelSheetRepository;
use Akbarjimi\ExcelImporter\Services\SheetDiscoveryService;

final readonly class HandleExcelUploaded
{
    public function __construct(
        private SheetDiscoveryService $discovery,
        private ExcelSheetRepository $sheetRepo,
    ) {}

    public function handle(ExcelUploaded $event): void
    {
        $sheetDTOs = $this->discovery->discover($event->file);

        $this->sheetRepo->bulkCreate($event->file->id, $sheetDTOs);

        event(new SheetsDiscovered($event->file->id));
    }
}
