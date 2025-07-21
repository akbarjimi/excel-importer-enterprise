<?php

namespace Akbarjimi\ExcelImporter\Repositories;

use Akbarjimi\ExcelImporter\Models\ExcelRow;

class ExcelRowStatusRepository
{
    public function markAsProcessed(int $rowId): void
    {
        ExcelRow::where('id', $rowId)->update(['status' => 'processed']);
    }

    public function markAsFailed(int $rowId): void
    {
        ExcelRow::where('id', $rowId)->update(['status' => 'failed']);
    }
}
