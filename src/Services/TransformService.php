<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Support\Facades\Config;

final class TransformService
{
    private array $transformers = [];

    /**
     * Load transformer callbacks for a specific sheet
     */
    public function load(ExcelSheet $sheet): void
    {
        $config = Config::get('excel-importer-transformers.' . $sheet->name . '.transformers', []);
        $this->transformers = $config;
    }

    /**
     * Apply transformers to a row's content
     */
    public function apply(array $row): array
    {
        foreach ($row as $column => $value) {
            if (isset($this->transformers[$column])) {
                $row[$column] = call_user_func($this->transformers[$column], $value);
            }
        }

        return $row;
    }
}