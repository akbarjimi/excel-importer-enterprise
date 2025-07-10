<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Models\ExcelRow;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;

class RowExtractionService
{
    public function extract(ExcelSheet $sheet): int
    {
        $file = $sheet->file;
        $path = storage_path("app/{$file->path}");

        $spreadsheet = IOFactory::load($path);
        $activeSheet = $spreadsheet->getSheetByName($sheet->name);
        $rowCount = 0;

        foreach ($activeSheet->toArray(null, true, true, true) as $row) {
            ExcelRow::create([
                'excel_sheet_id' => $sheet->id,
                'content' => json_encode($row),
                'content_hash' => md5(json_encode($row)),
            ]);
            $rowCount++;
        }

        return $rowCount;
    }
}
