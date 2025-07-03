<?php

use Illuminate\Support\Facades\Storage;
use Akbarjimi\ExcelImporter\Services\ImportManager;
use Akbarjimi\ExcelImporter\Models\ExcelFile;

beforeEach(function () {
    Storage::fake('local');

    $sample = file_get_contents(__DIR__.'/../stubs/sample.xlsx');
    Storage::put('imports/sample.xlsx', $sample);
});

it('stores Excel file metadata', function () {
    $binary = Storage::get('imports/sample.xlsx');

    $manager = new ImportManager();
    $file = $manager->import($binary, 'imports/sample.xlsx');

    expect($file)->toBeInstanceOf(ExcelFile::class)
        ->and($file->file_name)->toBe('sample.xlsx');
});
