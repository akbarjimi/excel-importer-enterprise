<?php

namespace Akbarjimi\ExcelImporter\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelFile extends Model
{
    protected $fillable = ['file_name','path','driver',];

    public function sheets()
    {
        return $this->hasMany(ExcelSheet::class);
    }
}
