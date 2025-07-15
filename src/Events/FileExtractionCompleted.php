<?php

namespace Akbarjimi\ExcelImporter\Events;

use Akbarjimi\ExcelImporter\Models\ExcelFile;

/**
 * Fired after all sheets and rows for a given Excel file have been extracted.
 * Can be used to trigger the transition into processing state.
 */
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