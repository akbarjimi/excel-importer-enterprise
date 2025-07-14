<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Events\RowFailed;
use Akbarjimi\ExcelImporter\Events\RowsExtracted;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;

class RowExtractionService implements OnEachRow, WithChunkReading, WithStartRow
{
    private ExcelSheet $sheet;
    private array $buffer = [];
    private int $inserted = 0;
    private int $batchSize;

    public function __construct()
    {
        $this->batchSize = config('excel-importer.insert_batch_size', 100); // customizable
    }

    public function extract(ExcelSheet $sheet): int
    {
        $this->sheet = $sheet;

        $sheet->file->update(['status' => 'reading']);

        Excel::import($this, $sheet->file->resolvedPath(), $sheet->file->driver);

        $sheet->update(['rows_extracted_at' => now()]);
        $sheet->file->update(['status' => ExcelFileStatus::READ]);
        $sheet->file->update(['rows_extracted' => $this->inserted,]);

        event(new RowsExtracted($sheet, $this->inserted));

        return $this->inserted;
    }

    public function onRow(Row $row): void
    {
        try {
            $raw = $row->toArray(null, true, true, true);
            $encoded = json_encode($raw, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

            $this->buffer[] = [
                'excel_sheet_id' => $this->sheet->id,
                'content' => $encoded,
                'content_hash' => md5($encoded),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($this->buffer) >= $this->batchSize) {
                $this->flushBuffer();
            }
        } catch (\Throwable $e) {
            Log::error('Row extraction failed: ' . $e->getMessage(), ['row' => $row->getIndex()]);
            event(new RowFailed($this->sheet, $row->getIndex(), $e->getMessage()));
        }
    }

    private function flushBuffer(): void
    {
        if (empty($this->buffer)) {
            return;
        }

        try {
            DB::table('excel_rows')->insert($this->buffer);
            $this->inserted += count($this->buffer);
            $this->buffer = [];
        } catch (\Throwable $e) {
            Log::critical('Bulk insert failed: ' . $e->getMessage());
        }
    }

    public function __destruct()
    {
        $this->flushBuffer();
    }

    public function chunkSize(): int
    {
        return config('excel-importer.chunk_size', 1000);
    }

    public function startRow(): int
    {
        return 1;
    }
}
