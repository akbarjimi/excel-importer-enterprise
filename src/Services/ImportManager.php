<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Event;

class ImportManager
{
    public function __construct(private Dispatcher $events)
    {
    }

    public function import(string $relativePath, string $driver = null): ExcelFile
    {
        $driver ??= config('excel-importer.default_disk', 'local');

        $file = ExcelFile::create([
            'file_name' => basename($relativePath),
            'path' => $relativePath,
            'driver' => $driver,
        ]);

        $this->events->dispatch(new ExcelUploaded($file));

        return $file;
    }
}