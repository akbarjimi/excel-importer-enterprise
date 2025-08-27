<?php

namespace Akbarjimi\ExcelImporter\Events;

final readonly class AllSheetsDispatched
{
    public function __construct(
        public int $fileId,
    ) {}
}
