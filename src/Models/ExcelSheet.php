<?php

namespace Akbarjimi\ExcelImporter\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelSheet extends Model
{
    protected $fillable = ['excel_file_id', 'name', 'rows_count', 'meta', 'rows_extracted_at',];

    public function file()
    {
        return $this->belongsTo(ExcelFile::class);
    }

    public function rows()
    {
        return $this->hasMany(ExcelRow::class);
    }
}