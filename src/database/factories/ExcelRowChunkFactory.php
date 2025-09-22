<?php

namespace Akbarjimi\ExcelImporter\Database\Factories;

use Akbarjimi\ExcelImporter\Models\ExcelRowChunk;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExcelRowChunkFactory extends Factory
{
    protected $model = ExcelRowChunk::class;

    public function definition(): array
    {
        return [
            'excel_sheet_id' => ExcelSheet::factory(),
            'from_row_id' => 1,
            'to_row_id' => 3,
            'size' => 3,
            'status' => 'pending',
            'mapping_status' => 'pending',
            'attempts' => 0,
            'error' => null,
            'dispatched_at' => null,
            'processed_at' => null,
        ];
    }
}
