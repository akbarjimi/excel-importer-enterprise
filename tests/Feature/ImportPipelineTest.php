<?php

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Services\ImportManager;
use Illuminate\Support\Facades\Event;

it('stores Excel file metadata in database', function () {
    $relativePath = __DIR__.'/../stubs/1sheet3rows1header.xlsx';
    $absolutePath = storage_path($relativePath);
    assert(file_exists($absolutePath), "Test file missing at: $absolutePath");

    Event::fake([ExcelUploaded::class]);

    $manager = app(ImportManager::class);
    $file = $manager->import($relativePath);

    expect($file)
        ->toBeInstanceOf(ExcelFile::class)
        ->file_name->toBe('1sheet3rows1header.xlsx')
        ->path->toBe($relativePath)
        ->status->toBe(ExcelFileStatus::PENDING);

    Event::assertDispatched(ExcelUploaded::class, fn ($event) => $event->file->id === $file->id);
});

it('stores Excel sheet metadata in database after file is uploaded', function () {
    $relativePath = __DIR__.'/../stubs/1sheet3rows1header.xlsx';
    $absolutePath = storage_path($relativePath);
    assert(file_exists($absolutePath), "Test file missing at: $absolutePath");

    $manager = app(ImportManager::class);
    $file = $manager->import($relativePath);

    $sheets = ExcelSheet::where('excel_file_id', $file->id)->get();

    expect($sheets)->not->toBeEmpty();
    expect($sheets->first()->name)->toBeString();
    expect($sheets->count())->toBeGreaterThan(0);
    expect($sheets->first()->rows_count)->toBeGreaterThan(0);
    expect($sheets->first()->status)->toBe(ExcelSheetStatus::PENDING->value);

});
