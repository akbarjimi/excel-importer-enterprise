<?php

namespace Akbarjimi\ExcelImporter\Events;

/**
 * Fired to queue processing for a specific chunk of rows.
 * Can be handled by a job or passed into a distributed queue system.
 */
readonly class ProcessRowsChunk
{
    public function __construct(
        public int $sheetId,
        public int $chunkIndex
    )
    {
    }
}
