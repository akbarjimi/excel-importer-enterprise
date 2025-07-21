<?php

namespace Akbarjimi\ExcelImporter\Repositories;

use Akbarjimi\ExcelImporter\Models\ExcelRow;

class ExcelRowRepository
{
    public function bulkInsert(array $rows): void
    {
        ExcelRow::insert($rows);
    }

    public function updateChunkIndex(int $sheetId, int $rowIndex, int $chunkIndex): void
    {
        ExcelRow::where('excel_sheet_id', $sheetId)
            ->where('row_index', $rowIndex)
            ->update(['chunk_index' => $chunkIndex]);
    }
}
