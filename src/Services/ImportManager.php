<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Illuminate\Support\Facades\DB;

readonly class ImportManager
{
    public function import(string $relativePath, ?string $driver = null): ExcelFile
    {
        $driver ??= config('excel-importer.default_disk');

        return DB::transaction(function () use ($relativePath, $driver) {

            $file = ExcelFile::create([
                'file_name' => basename($relativePath),
                'path' => $relativePath,
                'driver' => $driver,
                'status' => ExcelFileStatus::PENDING,
            ]);

            event(new ExcelUploaded($file));

            return $file;
        });
    }
}
