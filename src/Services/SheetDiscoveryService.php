<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SheetDiscoveryService
{
    public function discover(ExcelFile $file): array
    {
        $path = storage_path("app/{$file->path}");

        $spreadsheet = IOFactory::load($path);
        $sheets = $spreadsheet->getSheetNames();

        $sheetModels = [];

        foreach ($sheets as $index => $sheetName) {
            $sheet = $spreadsheet->getSheet($index);
            $rowCount = $sheet->getHighestRow();

            $sheetModels[] = ExcelSheet::create([
                'excel_file_id' => $file->id,
                'name' => $sheetName,
                'rows_count' => $rowCount,
                'meta' => json_encode([
                    'index' => $index,
                    'columns' => $sheet->getHighestColumn(),
                ]),
            ]);
        }

        return $sheetModels;
    }
}
