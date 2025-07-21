<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Illuminate\Support\Facades\Log;

readonly class SheetDiscoveryService
{
    public function discover(ExcelFile $file): array
    {
        $reader = IOFactory::createReaderForFile($file->resolvedPath());
        return $reader->listWorksheetInfo($file->resolvedPath());
    }
}
