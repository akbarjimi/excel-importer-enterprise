<?php

namespace Akbarjimi\ExcelImporter\Models;

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

final class ExcelFile extends Model
{
    protected $fillable = [
        'file_name',
        'path',
        'driver',
        'status',
    ];

    protected $casts = [
        'status' => ExcelFileStatus::class,
    ];

    // ---------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------

    public function sheets(): HasMany
    {
        return $this->hasMany(ExcelSheet::class);
    }

    // ---------------------------------------------------------------------
    // Accessors / Helpers (read‑only)
    // ---------------------------------------------------------------------

    public function resolvedPath(): string
    {
        return Storage::disk($this->driver)->path($this->path);
    }

    public function url(): string
    {
        return Storage::disk($this->driver)->url($this->path);
    }

    public function getReadableStatusAttribute(): string
    {
        return match ($this->status) {
            ExcelFileStatus::PENDING => 'File uploaded, no action yet',
            ExcelFileStatus::READING => 'File being parsed (sheets & rows)',
            ExcelFileStatus::ROWS_EXTRACTED => 'All rows read from Excel',
            ExcelFileStatus::PROCESSING => 'Chunks dispatched or in progress',
            ExcelFileStatus::COMPLETED => 'All chunks processed successfully',
            ExcelFileStatus::FAILED => 'General failure at any stage',
        };
    }
}
