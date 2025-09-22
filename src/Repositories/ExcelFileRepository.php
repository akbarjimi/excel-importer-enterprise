<?php

namespace Akbarjimi\ExcelImporter\Repositories;

use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExcelFileRepository
{
    public function incrementProcessedChunks(int $sheetId, int $by = 1): int
    {
        $updated = DB::update(
            'update excel_sheets set processed_chunks = processed_chunks + ? where id = ? and processed_chunks < chunk_count',
            [$by, $sheetId]
        );

        return (int) DB::table('excel_sheets')->where('id', $sheetId)->value('processed_chunks');
    }

    public function setSheetChunkCount(int $sheetId, int $count): void
    {
        DB::table('excel_sheets')->where('id', $sheetId)->update(['chunk_count' => (int) $count]);
    }

    public function allChunksProcessed(int $sheetId): bool
    {
        $row = DB::table('excel_sheets')->where('id', $sheetId)->first(['processed_chunks', 'chunk_count']);
        if (! $row) {
            return false;
        }

        return ((int) $row->chunk_count > 0) && ((int) $row->processed_chunks >= (int) $row->chunk_count);
    }

    public function incrementMappedCount(int $sheetId, int $by = 1): int
    {
        DB::table('excel_sheets')->where('id', $sheetId)->increment('mapped_count', $by);
        return (int) DB::table('excel_sheets')->where('id', $sheetId)->value('mapped_count');
    }

    public function markSheetMappedAtIfComplete(int $sheetId): void
    {
        $sheet = DB::table('excel_sheets')->where('id', $sheetId)->first(['mapped_count', 'rows_count']);
        if (! $sheet) {
            return;
        }

        if (! is_null($sheet->rows_count) && (int) $sheet->mapped_count >= (int) $sheet->rows_count) {
            DB::table('excel_sheets')->where('id', $sheetId)->update(['mapped_at' => Carbon::now()]);
        }
    }

    public function findFile(int $fileId): ?ExcelFile
    {
        return ExcelFile::with('excelSheets')->find($fileId);
    }

    public function findSheet(int $sheetId): ?ExcelSheet
    {
        return ExcelSheet::find($sheetId);
    }
}
