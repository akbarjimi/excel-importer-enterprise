<?php

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Services\RowExtractionService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

it('extracts rows and sets rows_extracted_at', function () {
    $rel = 'imports/two-row.xlsx';
    $abs = storage_path('app/' . $rel);
    File::ensureDirectoryExists(dirname($abs));

    $stubPath = __DIR__.'/../stubs/two-row.xlsx';
    File::copy($stubPath, $abs);

    $file = ExcelFile::create([
        'file_name' => 'two-row.xlsx',
        'path' => $rel,
        'driver' => 'local',
    ]);

    $sheet = $file->sheets()->create([
        'name' => 'Sheet1',
        'rows_count' => 2,
    ]);

    $inserted = app(RowExtractionService::class)->extract($sheet);

    $sheet->refresh();

    expect($inserted)->toBe(2)
        ->and($sheet->rows_extracted_at)->not()->toBeNull()
        ->and($sheet->rows()->count())->toBe(2);
});
