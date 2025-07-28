<?php

namespace Akbarjimi\ExcelImporter\Events;

readonly class SheetsDiscovered
{
    public function __construct(
        public int $fileId,
    )
    {
    }
}