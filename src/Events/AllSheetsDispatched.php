<?php

namespace Akbarjimi\ExcelImporter\Events;

readonly class AllSheetsDispatched
{
    public function __construct(
        public int $fileId,
    ) {}
}
