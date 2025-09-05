<?php

namespace Akbarjimi\ExcelImporter\Repositories;

use Akbarjimi\ExcelImporter\Models\ExcelRow;

class ExcelRowRepository
{
    public function bulkUpsert(array $rows): void
    {
        ExcelRow::upsert(
            $rows,
            ['excel_sheet_id', 'content_hash', 'hash_algo'],
            ['content', 'status', 'chunk_index', 'row_index', 'updated_at']
        );
    }
}
