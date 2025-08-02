<?php

namespace Akbarjimi\ExcelImporter\Models;

use Akbarjimi\ExcelImporter\Database\Factories\ExcelSheetFactory;
use Akbarjimi\ExcelImporter\Enums\ExcelSheetStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExcelSheet extends Model
{
    use HasFactory;

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
        'status' => ExcelSheetStatus::class,
    ];

    public function excelFile(): BelongsTo
    {
        return $this->belongsTo(ExcelFile::class, 'excel_file_id');
    }

    public function excelRow(): HasMany
    {
        return $this->hasMany(ExcelRow::class);
    }

    protected static function newFactory(): ExcelSheetFactory
    {
        return ExcelSheetFactory::new();
    }
}
