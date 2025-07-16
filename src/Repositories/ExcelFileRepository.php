<?php

namespace Akbarjimi\ExcelImporter\Repositories;

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ExcelFileRepository
{
    public function create(array $data): ExcelFile
    {
        return ExcelFile::create($data);
    }

    public function updateStatus(int $id, string $status): bool
    {
        return ExcelFile::where('id', $id)->update(['status' => $status]) > 0;
    }

    public function find(int $id): ?ExcelFile
    {
        return ExcelFile::find($id);
    }

    public function incrementExtractedRows(int $id, int $by = 1): void
    {
        ExcelFile::where('id', $id)->increment('rows_extracted', $by);
    }
}