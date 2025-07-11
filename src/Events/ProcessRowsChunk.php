<?php

namespace Akbarjimi\ExcelImporter\Events;

class ProcessRowsChunk
{
    public function __construct(
        public int $sheetId,
        public int $chunkIndex
    )
    {
    }
}
