<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\SheetDiscovered;
use Akbarjimi\ExcelImporter\Services\RowExtractionService;

final readonly class HandleSheetDiscovered
{
    public function __construct(
        private RowExtractionService $extractor,
    ) {}

    public function handle(SheetDiscovered $event): void
    {
        $this->extractor->extract($event->sheet);
    }
}
