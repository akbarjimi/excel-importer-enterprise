<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

final class ValidateService
{
    private array $rules = [];

    /**
     * Load validation rules for a specific sheet
     */
    public function load(ExcelSheet $sheet): void
    {
        $rules = Config::get('excel-importer-sheets.'.$sheet->name.'.validation', []);
        $this->rules = $rules;
    }

    /**
     * Validate a row and return validation errors, if any
     */
    public function apply(array $payload): array
    {
        // TODO: what happen if rules do not load and this line said
        // row contents are ok?
        if (empty($this->rules)) {
            return [];
        }

        $validator = Validator::make($payload, $this->rules);

        return $validator->fails() ? $validator->errors()->toArray() : [];
    }
}
