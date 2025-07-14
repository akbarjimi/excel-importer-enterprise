<?php

namespace Akbarjimi\ExcelImporter\Events;

use Akbarjimi\ExcelImporter\Models\ExcelSheet;

readonly class RowsExtracted
{
    public function __construct(
        public ExcelSheet $sheet,
        public int        $insertedCount
    ) {}
}
