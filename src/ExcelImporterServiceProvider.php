<?php

namespace Akbarjimi\ExcelImporter;

use Akbarjimi\ExcelImporter\Events\AllSheetsDispatched;
use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Events\SheetDiscovered;
use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Listeners\HandleAllSheetsDispatched;
use Akbarjimi\ExcelImporter\Listeners\HandleExcelUploaded;
use Akbarjimi\ExcelImporter\Listeners\HandleSheetDiscovered;
use Akbarjimi\ExcelImporter\Listeners\HandleSheetsDiscovered;
use Akbarjimi\ExcelImporter\Services\RowExtractionService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ExcelImporterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerEventListeners();
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->publishes([
            __DIR__.'/config/excel-importer.php' => config_path('excel-importer.php'),
        ], 'config');
    }

    public function register()
    {
        $this->app->bind(ChunkService::class);
        $this->app->bind(RowExtractionService::class);

        $this->mergeConfigFrom(
            __DIR__.'/config/excel-importer.php', 'excel-importer'
        );
        $this->loadFactoriesFrom(__DIR__.'/database/factories');
    }

    public function registerEventListeners(): void
    {
        Event::listen(ExcelUploaded::class, HandleExcelUploaded::class);
        Event::listen(SheetsDiscovered::class, HandleSheetsDiscovered::class);
        Event::listen(SheetDiscovered::class, HandleSheetDiscovered::class);
        Event::listen(AllSheetsDispatched::class, HandleAllSheetsDispatched::class);
    }
}
