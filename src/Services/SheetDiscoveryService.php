<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Support\ExcelReaderAdapter;
use Illuminate\Support\Facades\Log;

readonly class SheetDiscoveryService
{
    public function __construct(
        private ExcelReaderAdapter $readerAdapter
    )
    {
    }

    public function discover(ExcelFile $file): array
    {
        try {
            $sheetInfoList = $this->readerAdapter->getSheetMetadata($file);
            $sheetModels = [];

            foreach ($sheetInfoList as $index => $sheetMeta) {
                $sheetModels[] = ExcelSheet::create([
                    'excel_file_id' => $file->id,
                    'name' => $sheetMeta['name'],
                    'rows_count' => $sheetMeta['totalRows'] ?? 0,
                    'meta' => json_encode(['index' => $index]),
                ]);
            }

            $file->update(['status' => ExcelFileStatus::READ]);

            return $sheetModels;
        } catch (\Throwable $e) {
            Log::error('Sheet discovery failed: ' . $e->getMessage());
            $file->update(['status' => ExcelFileStatus::FAILED]);
            return [];
        }
    }
}
