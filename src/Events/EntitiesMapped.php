<?php

namespace Akbarjimi\ExcelImporter\Events;

final readonly class EntitiesMapped
{
    public function __construct(
        public int $sheetId,
        public int $mappedCount
    )
    {
    }
}
