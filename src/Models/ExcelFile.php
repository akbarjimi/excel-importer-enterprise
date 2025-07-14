<?php

namespace Akbarjimi\ExcelImporter\Models;

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Illuminate\Database\Eloquent\Model;

class ExcelFile extends Model
{
    protected $fillable = ['file_name','path','driver',];

    protected $casts = [
        'status' => ExcelFileStatus::class,
    ];

    public function sheets()
    {
        return $this->hasMany(ExcelSheet::class);
    }
}
