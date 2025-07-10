<?php

namespace Akbarjimi\ExcelImporter\Events;

use Illuminate\Support\Collection;

class SheetsDiscovered
{
    public function __construct(public Collection $sheets) {}
}
