<?php

use Akbarjimi\ExcelImporter\Services\SheetDiscoveryService;
use Akbarjimi\ExcelImporter\Models\ExcelFile;

it('discovers sheets from Excel file', function () {
    $file = ExcelFile::create([
        'file_name' => 'sample.xlsx',
        'path' => base_path('tests/stubs/sample.xlsx'),
        'driver' => 'local',
    ]);

    $service = new SheetDiscoveryService();
    $sheets = $service->discover($file);

    expect($sheets)->not()->toBeEmpty();
});
