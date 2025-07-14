<?php

namespace Akbarjimi\ExcelImporter\Events;

use Akbarjimi\ExcelImporter\Models\ExcelFile;

readonly class FileExtractionCompleted
{
    public function __construct(
        public int $fileId,
        public int $totalSheets,
        public int $totalRows,
    )
    {
    }

    public static function fromFile(ExcelFile $file): self
    {
        return new self(
            fileId: $file->id,
            totalSheets: $file->sheets()->count(),
            totalRows: $file->sheets()->withCount('rows')->get()->sum('rows_count')
        );
    }
}