<?php

namespace Akbarjimi\ExcelImporter\Events;

use Akbarjimi\ExcelImporter\Models\ExcelSheet;

/**
 * Fired after all rows from a single sheet have been extracted and saved.
 * Allows the system to track per-sheet progress.
 */
readonly class RowsExtracted
{
    public function __construct(
        public ExcelSheet $sheet,
        public int        $insertedCount
    )
    {
    }
}
