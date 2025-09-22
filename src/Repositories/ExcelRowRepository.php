<?php

namespace Akbarjimi\ExcelImporter\Repositories;

use Illuminate\Support\Facades\DB;

final class ExcelRowRepository
{
    public function bulkUpsert(array $rows, int $chunkSize = 500): void
    {
        collect($rows)
            ->chunk($chunkSize)
            ->each(function ($chunk) {
                $sanitized = collect($chunk)->map(function ($row) {
                    unset($row['id']);
                    return $row;
                })->all();

                DB::table('excel_rows')->upsert(
                    $sanitized,
                    ['excel_sheet_id', 'content_hash', 'hash_algo'],
                    ['content', 'status', 'chunk_index', 'row_index', 'updated_at']
                );
            });
    }
}
