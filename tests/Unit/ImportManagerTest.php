<?php

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Services\ImportManager;
use Illuminate\Support\Facades\Event;
use Akbarjimi\ExcelImporter\Events\ExcelUploaded;

it('stores Excel metadata and dispatches event', function () {
    Event::fake();

    $manager = new ImportManager();
    $file = $manager->import('storage/app/imports/sample.xlsx');

    expect($file)->toBeInstanceOf(ExcelFile::class)
        ->and($file->file_name)->toBe('sample.xlsx');

    Event::assertDispatched(ExcelUploaded::class);
});
