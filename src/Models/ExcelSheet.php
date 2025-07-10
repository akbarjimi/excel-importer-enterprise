<?php

namespace Akbarjimi\ExcelImporter\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelSheet extends Model
{
    protected $fillable = ['excel_file_id','name','rows_count','meta',];
}