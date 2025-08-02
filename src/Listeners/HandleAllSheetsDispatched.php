<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\SheetDiscovered;

final readonly class HandleAllSheetsDispatched
{
    public function __construct() {}

    public function handle(SheetDiscovered $event): void
    {
        // do nothing for now
    }
}
