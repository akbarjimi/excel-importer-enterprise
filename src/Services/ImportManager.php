<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportManager
{
    public function import(string $binaryContents, string $originalPath): ExcelFile
    {
        $tmpDir = storage_path('app/tmp');

        if (!File::exists($tmpDir)) {
            File::makeDirectory($tmpDir, recursive: true);
        }

        $tempPath = $tmpDir . '/' . Str::random(40) . '.xlsx';
        file_put_contents($tempPath, $binaryContents);

        return ExcelFile::create([
            'file_name' => basename($originalPath),
        ]);
    }
}
