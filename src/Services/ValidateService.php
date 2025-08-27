<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class ValidateService
{
    public function apply(array $data, ExcelRow $row): void
    {
        $rules = config('excel-importer.validation.'. $row->excel_sheet_id, []);
        if (!$rules) return;

        $v = Validator::make($data, $rules);
        if ($v->fails()) {
            throw new ValidationException($v);
        }
    }
}
