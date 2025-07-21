<?php

namespace Akbarjimi\ExcelImporter;

use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Events\FileExtractionCompleted;
use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Listeners\HandleExcelUploaded;
use Akbarjimi\ExcelImporter\Listeners\HandleFileExtractionCompleted;
use Akbarjimi\ExcelImporter\Listeners\HandleSheetsDiscovered;
use Akbarjimi\ExcelImporter\Services\ChunkService;
use Akbarjimi\ExcelImporter\Services\RowExtractionService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ExcelImporterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        Event::listen(ExcelUploaded::class, HandleExcelUploaded::class);
        Event::listen(SheetsDiscovered::class, HandleSheetsDiscovered::class);
        Event::listen(FileExtractionCompleted::class, HandleFileExtractionCompleted::class);
        $this->publishes([
            __DIR__.'/config/excel-importer.php' => config_path('excel-importer.php'),
        ], 'config');
    }

    public function register()
    {
        $this->app->bind(ChunkService::class);
        $this->app->bind(RowExtractionService::class);
    }
}
