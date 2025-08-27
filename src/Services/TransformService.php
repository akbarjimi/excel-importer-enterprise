<?php

namespace Akbarjimi\ExcelImporter\Services;

use Akbarjimi\ExcelImporter\Models\ExcelRow;

final class TransformService
{
    public function apply(ExcelRow $row): array
    {
        $payload = json_decode($row->content, true, 512, JSON_THROW_ON_ERROR);
        // apply configured transformers
        return $payload;
    }
}
