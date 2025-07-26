<?php

namespace Akbarjimi\ExcelImporter\Events;

/**
 * Fired after the Excel reader detects all sheets from the file.
 * Passes sheet metadata for further processing and persistence.
 */
readonly class SheetsDiscovered
{
    public function __construct(
        public int $fileId
    ) {}
}
