<?php

namespace Akbarjimi\ExcelImporter\Events;

use Illuminate\Support\Collection;

/**
 * Fired after the Excel reader detects all sheets from the file.
 * Passes sheet metadata for further processing and persistence.
 */
readonly class SheetsDiscovered
{
    public function __construct(
        public Collection $sheets // Collection of SheetMeta DTOs or raw arrays
    ) {}
}
