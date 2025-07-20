<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;

readonly class ImportManager
{
    public function __construct(private Dispatcher $events)
    {
    }

    public function import(string $relativePath, ?string $driver = null): ExcelFile
    {
        $driver ??= config('excel-importer.default_disk', 'local');

        return DB::transaction(function () use ($relativePath, $driver) {

            /** @var ExcelFile $file */
            $file = ExcelFile::create([
                'file_name' => basename($relativePath),
                'path' => $relativePath,
                'driver' => $driver,
                'status' => ExcelFileStatus::PENDING,
            ]);

            // Immediate transition → READING so workers know they can start extraction
            $file->updateQuietly(['status' => ExcelFileStatus::READING]);

            $this->events->dispatch(new ExcelUploaded($file));

            return $file->fresh();
        });
    }
}
