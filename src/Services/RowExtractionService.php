<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Akbarjimi\ExcelImporter\Events\AllSheetsDispatched;
use Akbarjimi\ExcelImporter\Events\RowFailed;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;
use Throwable;

class RowExtractionService implements OnEachRow, WithChunkReading, WithStartRow
{
    protected ExcelSheet $sheet;

    protected array $buffer = [];

    protected int $inserted = 0;

    protected int $batchSize;

    public function __construct()
    {
        $this->batchSize = config('excel-importer.insert_batch_size', 100);
    }

    public function extract(ExcelSheet $sheet): int
    {
        $this->sheet = $sheet;
        $this->setFileStatus(ExcelFileStatus::READING);

        try {
            Excel::import($this, $sheet->excelFile->path, $sheet->excelFile->driver);

            $this->flushBuffer();

            $sheet->update(['rows_extracted_at' => now()]);
            $this->setFileStatus(ExcelFileStatus::ROWS_EXTRACTED);

            $sheet->excelFile->update([
                'rows_extracted' => $this->inserted,
            ]);

            if ($sheet->excelFile->excelSheets()->whereNull('rows_extracted_at')->doesntExist()) {
                event(new AllSheetsDispatched($sheet->excelFile->getKey()));
            }
        } catch (Throwable $e) {
            Log::critical('Excel import failed: '.$e->getMessage(), [
                'sheet_id' => $sheet->id,
                'file_id' => $sheet->excel_file_id,
            ]);

            $this->setFileStatus(ExcelFileStatus::FAILED);
            throw_if(app()->isLocal(), $e);
        }

        return $this->inserted;
    }

    public function onRow(Row $row): void
    {
        try {
            $raw = $row->toArray(null, true, true, false);
            if (! is_array($raw)) {
                throw new \RuntimeException('Invalid row: not an array.');
            }
            $encoded = json_encode($raw, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $hashAlgo = config('excel-importer.hash_algo');

            $this->buffer[] = [
                'excel_sheet_id' => $this->sheet->id,
                'content' => $encoded,
                'hash_algo' => $hashAlgo,
                'content_hash' => hash($hashAlgo, $encoded),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($this->buffer) >= $this->batchSize) {
                $this->flushBuffer();
            }
        } catch (Throwable $e) {
            Log::error("Row extraction failed at row {$row->getIndex()}: {$e->getMessage()}", [
                'sheet_id' => $this->sheet->id,
            ]);

            event(new RowFailed($this->sheet, $row->getIndex(), $e->getMessage()));
        }
    }

    protected function flushBuffer(): void
    {
        if (empty($this->buffer)) {
            return;
        }

        try {
            DB::table('excel_rows')->upsert(
                $this->buffer,
                ['excel_sheet_id', 'content_hash', 'hash_algo'],
                ['updated_at']
            );

            $this->inserted += count($this->buffer);
        } catch (Throwable $e) {
            Log::critical('Bulk insert failed: '.$e->getMessage(), [
                'sheet_id' => $this->sheet->id,
            ]);
        } finally {
            $this->buffer = [];
        }
    }

    protected function setFileStatus(ExcelFileStatus $status): void
    {
        optional($this->sheet->excelFile)->update(['status' => $status->value]);
    }

    public function chunkSize(): int
    {
        return config('excel-importer.chunk_size', 1000);
    }

    public function startRow(): int
    {
        return 1;
    }

    public function __destruct()
    {
        $this->flushBuffer();
    }
}
