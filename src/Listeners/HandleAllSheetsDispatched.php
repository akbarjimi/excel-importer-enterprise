<?php

namespace Akbarjimi\ExcelImporter\Listeners;

use Akbarjimi\ExcelImporter\Events\AllSheetsDispatched;

final readonly class HandleAllSheetsDispatched
{
    public function __construct() {}

    public function handle(AllSheetsDispatched $event): void
    {
        // do nothing for now
    }
}
