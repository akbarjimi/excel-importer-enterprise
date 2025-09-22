<?php

namespace Akbarjimi\ExcelImporter\Models;

use Database\Factories\ExcelRowChunkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class ExcelRowChunk extends Model
{
    use HasFactory;

    protected $fillable = [
        'excel_sheet_id', 'from_row_id', 'to_row_id', 'size',
        'status', 'attempts', 'error', 'dispatched_at', 'processed_at',
    ];

    protected $casts = [
        'dispatched_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // ---------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------

    public function excelSheet(): BelongsTo
    {
        return $this->belongsTo(ExcelSheet::class, 'excel_sheet_id');
    }

    protected static function newFactory(): ExcelRowChunkFactory
    {
        return ExcelRowChunkFactory::new();
    }
}
