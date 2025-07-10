<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Illuminate\Support\Facades\Event;

class ImportManager
{
    public function import(string $path, string $driver = 'local'): ExcelFile
    {
        $fileName = basename($path);

        $file = ExcelFile::create([
            'file_name' => $fileName,
            'path' => $path,
            'driver' => $driver,
        ]);

        Event::dispatch(new ExcelUploaded($file));

        return $file;
    }
}
