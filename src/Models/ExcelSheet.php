<?php

namespace Akbarjimi\ExcelImporter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExcelSheet extends Model
{
    protected $fillable = [
        'excel_file_id',
        'name',
        'rows_count',
        'meta',
        'rows_extracted_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'rows_extracted_at' => 'datetime',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(ExcelFile::class, 'excel_file_id');
    }

    public function rows(): HasMany
    {
        return $this->hasMany(ExcelRow::class);
    }
}
