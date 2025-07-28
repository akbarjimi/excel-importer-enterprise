<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\SheetDiscovered;
use Akbarjimi\ExcelImporter\Services\RowExtractionService;
use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class HandleSheetDiscovered implements ShouldQueue
{
    public function __construct(
        private RowExtractionService $extractor,
    ) {}

    public function handle(SheetDiscovered $event): void
    {
        $this->extractor->extract($event->sheet);
    }
}
