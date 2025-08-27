<?php

namespace Akbarjimi\ExcelImporter\Repositories;

use Akbarjimi\ExcelImporter\Models\ExcelRow;

class ExcelRowRepository
{
    public function bulkInsert(array $rows): void
    {
        ExcelRow::insert($rows);
    }

    public function bulkUpsert(array $rows): void
    {
        ExcelRow::upsert(
            $rows,
            ['excel_sheet_id', 'content_hash', 'hash_algo'],
            ['content', 'status', 'chunk_index', 'row_index', 'updated_at']
        );
    }

    public function updateChunkIndex(int $sheetId, int $rowIndex, int $chunkIndex): void
    {
        ExcelRow::where('excel_sheet_id', $sheetId)
            ->where('row_index', $rowIndex)
            ->update(['chunk_index' => $chunkIndex]);
    }
}
