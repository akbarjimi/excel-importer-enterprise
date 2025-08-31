<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class ValidateService
{
    private array $rules = [];

    /**
     * Load validation rules for a specific sheet
     */
    public function load(ExcelSheet $sheet): void
    {
        $this->rules = Config::get('excel-importer-transformers.' . $sheet->name . '.validation', []);
    }

    /**
     * Validate a row and return validation errors, if any
     */
    public function apply(array $payload): array
    {
        if (empty($this->rules)) {
            return [];
        }

        $validator = Validator::make($payload, $this->rules);

        return $validator->fails() ? $validator->errors()->toArray() : [];
    }
}
