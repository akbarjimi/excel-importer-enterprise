<?php

namespace Akbarjimi\ExcelImporter\Events;

use Akbarjimi\ExcelImporter\Models\ExcelFile;

class ExcelUploaded
{
    public function __construct(public ExcelFile $file)
    {
    }
}
