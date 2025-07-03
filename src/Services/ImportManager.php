<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportManager
{
    public function import(string $binaryContents, string $originalPath): ExcelFile
    {
        $tempPath = storage_path('app/tmp/' . Str::random(40) . '.xlsx');
        file_put_contents($tempPath, $binaryContents);

        $spreadsheet = IOFactory::load($tempPath);

        return ExcelFile::create([
            'file_name' => basename($originalPath),
        ]);
    }
}
