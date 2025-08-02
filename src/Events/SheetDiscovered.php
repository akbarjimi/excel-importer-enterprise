<?php

namespace Akbarjimi\ExcelImporter\Events;

use Akbarjimi\ExcelImporter\Models\ExcelSheet;

readonly class SheetDiscovered
{
    public function __construct(
        public ExcelSheet $sheet,
        public bool $isLast = false,
    ) {}
}
