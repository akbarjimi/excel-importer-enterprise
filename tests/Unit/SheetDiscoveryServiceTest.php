<?php

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Services\SheetDiscoveryService;
use Illuminate\Support\Facades\File;

it('discovers sheets from Excel file', function () {
    $storageRelativePath = 'imports/1sheet3rows1header.xlsx';
    $fullPath = storage_path("app/{$storageRelativePath}");

    File::ensureDirectoryExists(dirname($fullPath));
    File::copy(__DIR__.'/../stubs/1sheet3rows1header.xlsx', $fullPath);

    $file = ExcelFile::create([
        'file_name' => 'sample.xlsx',
        'path' => $storageRelativePath,
        'driver' => 'local',
    ]);

    $service = new SheetDiscoveryService;
    $sheets = $service->discover($file);

    expect($sheets)->not()->toBeEmpty();

    File::delete($fullPath);
});
