<?php

use Akbarjimi\ExcelImporter\Services\SheetDiscoveryService;
use Akbarjimi\ExcelImporter\Models\ExcelFile;

beforeEach(function () {
    Storage::fake('local');

    $binary = file_get_contents(__DIR__.'/../stubs/sample.xlsx');
    Storage::put('imports/sample.xlsx', $binary);
});

it('discovers sheets from Excel file', function () {
    $realPath = Storage::disk('local')->path('imports/sample.xlsx');

    $file = ExcelFile::create([
        'file_name' => 'sample.xlsx',
        'path' => $realPath,
        'driver' => 'local',
    ]);

    $service = new SheetDiscoveryService();
    $sheets = $service->discover($file);

    expect($sheets)->not()->toBeEmpty();
});
