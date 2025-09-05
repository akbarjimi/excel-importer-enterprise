<?php

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Services\RowExtractionService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
});

it('extracts rows and sets rows_extracted_at', function () {
    $relative = 'imports/1sheet3rows1header.xlsx';
    $absolute = storage_path('app/'.$relative);
    File::ensureDirectoryExists(dirname($absolute));

    File::copy(__DIR__.'/../stubs/1sheet3rows1header.xlsx', $absolute);

    $file = ExcelFile::create([
        'file_name' => 'two-row.xlsx',
        'path' => $relative,
        'driver' => 'local',
    ]);

    $sheet = $file->sheets()->create([
        'name' => 'Sheet1',
        'rows_count' => 2,
    ]);

    $service = app(RowExtractionService::class);
    $inserted = $service->extract($sheet);

    $sheet->refresh();

    expect($inserted)->toBe(2)
        ->and($sheet->rows_extracted_at)->not()->toBeNull()
        ->and($sheet->rows()->count())->toBe(2);
});
