<?php

namespace Akbarjimi\ExcelImporter\Events;

use Akbarjimi\ExcelImporter\Models\ExcelFile;

readonly class AllSheetsDispatched
{
    public function __construct(
        public ExcelFile $file,
    )
    {
    }
}
