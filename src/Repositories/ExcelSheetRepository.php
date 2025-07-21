<?php

namespace Akbarjimi\ExcelImporter\Repositories;

use Akbarjimi\ExcelImporter\DTOs\SheetDTO;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;

class ExcelSheetRepository
{
    public function createFromDTO(SheetDTO $dto): ExcelSheet
    {
        return ExcelSheet::create($dto->toArray());
    }

    public function findById(int $id): ?ExcelSheet
    {
        return ExcelSheet::find($id);
    }

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
}
