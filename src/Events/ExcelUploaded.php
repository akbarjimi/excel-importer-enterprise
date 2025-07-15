<?php

namespace Akbarjimi\ExcelImporter\Events;

use Akbarjimi\ExcelImporter\Models\ExcelFile;

/**
 * Fired after an Excel file is uploaded and registered.
 * This marks the entry point into the import pipeline.
 */
readonly class ExcelUploaded
{
    public function __construct(public ExcelFile $file)
    {
    }
}
