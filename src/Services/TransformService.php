<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelRow;

final class TransformService
{
    public function apply(ExcelRow $row): array
    {
        $payload = $row->content;
        // apply configured transformers
        return $payload;
    }
}
