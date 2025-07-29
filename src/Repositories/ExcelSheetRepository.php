<?php

namespace Akbarjimi\ExcelImporter\Repositories;

use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Database\Eloquent\Collection;

class ExcelSheetRepository
{
    public function bulkCreate(int $fileId, array $sheets): void
    {
        foreach ($sheets as $sheet) {
            ExcelSheet::create([
                'excel_file_id' => $fileId,
                'name' => $sheet['worksheetName'] ?? 'Unnamed',
                'rows_count' => $sheet['totalRows'] ?? 0,
                'meta' => json_encode($sheet),
            ]);
        }
    }

    public function getByFileId(int $fileId): Collection
    {
        return ExcelSheet::where('excel_file_id', $fileId)->get();
    }
}
