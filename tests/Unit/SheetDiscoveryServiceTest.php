<?php

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Services\SheetDiscoveryService;
use Illuminate\Support\Facades\File;

it('discovers sheets from Excel file', function () {
    $source = __DIR__ . '/../stubs/sample.xlsx';

    $file = ExcelFile::create([
        'file_name' => 'sample.xlsx',
        'path' => $source,
        'driver' => 'local',
    ]);

    $service = new SheetDiscoveryService();
    $sheets = $service->discover($file);

    expect($sheets)->not()->toBeEmpty();
});
