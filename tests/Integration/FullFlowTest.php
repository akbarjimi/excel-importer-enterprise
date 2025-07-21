<?php

use Akbarjimi\ExcelImporter\Events\FileExtractionCompleted;
use Akbarjimi\ExcelImporter\Services\ImportManager;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

it('imports, discovers, extracts rows, and fires FileExtractionCompleted', function () {
    Event::fake([FileExtractionCompleted::class]);

    $rel = 'imports/big.xlsx';
    $abs = storage_path('app/'.$rel);
    File::ensureDirectoryExists(dirname($abs));
    File::copy(base_path('tests/stubs/big.xlsx'), $abs);

    $file = app(ImportManager::class)->import($rel);

    app()->make(Kernel::class)->call('queue:work --once');

    $file->refresh();
    expect($file->sheets()->count())->toBe(2)
        ->and($file->sheets()->whereNull('rows_extracted_at')->count())->toBe(0)
        ->and($file->sheets->flatMap->rows->count())->toBe(4000);

    Event::assertDispatched(FileExtractionCompleted::class);
});
