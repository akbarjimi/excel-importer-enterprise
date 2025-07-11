<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelRow;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\IOFactory;


class RowExtractionService implements OnEachRow, WithChunkReading, WithStartRow
{
    private ExcelSheet $sheet;
    private int $inserted = 0;

    public function extract(ExcelSheet $sheet): int
    {
        $this->sheet = $sheet;
        Excel::import($this, $sheet->file->resolvedPath(), $sheet->file->driver);
        $sheet->update(['rows_extracted_at' => now()]);
        return $this->inserted;
    }

    public function onRow(Row $row): void
    {
        $values = $row->toArray(null, true, true, true);
        ExcelRow::create([
            'excel_sheet_id' => $this->sheet->id,
            'content' => json_encode($values),
            'content_hash' => md5(json_encode($values)),
        ]);
        $this->inserted++;
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