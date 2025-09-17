<?php

namespace Akbarjimi\ExcelImporter\Events;

final readonly class AllChunksCompleted
{
    public function __construct(
        public int $sheetId,
    ) {}
}
