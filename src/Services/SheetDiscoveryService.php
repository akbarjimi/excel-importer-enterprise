<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SheetDiscoveryService
{
    public function discover(ExcelFile $file): array
    {
        $spreadsheet = Excel::toArray(null, $file->resolvedPath(), $file->driver)[0] ?? [];

        $reader = IOFactory::createReaderForFile($file->resolvedPath());
        $sheetNames = $reader->listWorksheetNames($file->resolvedPath());

        $sheetModels = [];
        foreach ($sheetNames as $index => $name) {
            /** @var Worksheet $metaSheet */
            $metaSheet = $reader->listWorksheetInfo($file->resolvedPath())[$index];
            $rowCount = $metaSheet['totalRows'] ?? 0;

            $sheetModels[] = ExcelSheet::create([
                'excel_file_id' => $file->id,
                'name' => $name,
                'rows_count' => $rowCount,
                'meta' => json_encode(['index' => $index]),
            ]);
        }

        return $sheetModels;
    }
}
