<?php

namespace Akbarjimi\ExcelImporter\Repositories;

use Akbarjimi\ExcelImporter\DTOs\SheetDTO;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;

class ExcelSheetRepository
{
    public function createFromDTO(SheetDTO $dto): ExcelSheet
    {
        return ExcelSheet::create($dto->toArray());
    }

    public function findById(int $id): ?ExcelSheet
    {
        return ExcelSheet::find($id);
    }
}
