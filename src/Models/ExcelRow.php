<?php

namespace Akbarjimi\ExcelImporter\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelRow extends Model
{
    protected $fillable = ['excel_sheet_id','content','is_processed','content_hash',];
}