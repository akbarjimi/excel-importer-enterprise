<?php

namespace Akbarjimi\ExcelImporter\Database\Factories;

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExcelFileFactory extends Factory
{
    protected $model = ExcelFile::class;

    public function definition(): array
    {
        return [
            'file_name' => $this->faker->word() . '.xlsx',
            'path' => 'testing/' . $this->faker->uuid() . '.xlsx',
            'driver' => 'local',
            'status' => ExcelFileStatus::PENDING->value,
            'extracted_at' => null,
            'processed_at' => null,
            'failed_at' => null,
            'meta' => null,
            'exception' => null,
            'owner_id' => null,
            'owner_type' => null,
        ];
    }
}
