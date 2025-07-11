<?php

namespace Akbarjimi\ExcelImporter\Events;

use Akbarjimi\ExcelImporter\Models\ExcelFile;

class FileExtractionCompleted
{
    public function __construct(public ExcelFile $file)
    {
    }
}
