<?php

namespace Akbarjimi\ExcelImporter\Database\Factories;

use Akbarjimi\ExcelImporter\Enums\ExcelSheetStatus;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExcelSheetFactory extends Factory
{
    protected $model = ExcelSheet::class;

    public function definition(): array
    {
        return [
            'excel_file_id' => ExcelFile::factory(),

            'name' => $this->faker->word(),

            'status' => $this->faker->randomElement(ExcelSheetStatus::cases())->value,

            'rows_count' => $this->faker->optional()->numberBetween(1, 1000),

            'rows_extracted_at' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),

            'chunk_count' => $this->faker->optional()->numberBetween(1, 20),

            'meta' => $this->faker->optional()->json(),

            'exception' => $this->faker->optional()->realText(100),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn() => [
            'status' => ExcelSheetStatus::PENDING->value,
            'rows_extracted_at' => null,
        ]);
    }

    public function extracted(): static
    {
        return $this->state(fn() => [
            'status' => ExcelSheetStatus::EXTRACTED->value,
            'rows_extracted_at' => now(),
        ]);
    }

    public function failed(string $exception = null): static
    {
        return $this->state(fn() => [
            'status' => ExcelSheetStatus::FAILED->value,
            'exception' => $exception ?? 'Unhandled exception during extraction.',
        ]);
    }

    public function withRowsCount(int $count): static
    {
        return $this->state(fn() => [
            'rows_count' => $count,
        ]);
    }

    public function withChunks(int $chunks): static
    {
        return $this->state(fn() => [
            'chunk_count' => $chunks,
        ]);
    }
}
