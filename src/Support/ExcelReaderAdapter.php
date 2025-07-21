<?php

namespace Akbarjimi\ExcelImporter\Support;

namespace Akbarjimi\ExcelImporter\Support;

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelReaderAdapter
{
    public function getSheetMetadata(ExcelFile $file): array
    {
        $reader = IOFactory::createReaderForFile($file->resolvedPath());

        return $reader->listWorksheetInfo($file->resolvedPath());
    }
}
