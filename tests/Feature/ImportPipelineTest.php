<?php

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Managers\ImportManager;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Illuminate\Support\Facades\Event;

it('stores Excel file metadata in database', function () {
    $relativePath = __DIR__ . '/../stubs/1sheet3rows1header.xlsx';
    $absolutePath = storage_path($relativePath);
    assert(file_exists($absolutePath), "Test file missing at: $absolutePath");

    Event::fake([ExcelUploaded::class]);

    $manager = new ImportManager(events());
    $file = $manager->import($relativePath);

    expect($file)
        ->toBeInstanceOf(ExcelFile::class)
        ->file_name->toBe('1sheet3rows1header.xlsx')
        ->path->toBe($relativePath)
        ->status->toBe(ExcelFileStatus::READING);

    Event::assertDispatched(ExcelUploaded::class, fn($event) => $event->file->id === $file->id);
});
