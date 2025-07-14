<?php

namespace Akbarjimi\ExcelImporter\Models;

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class ExcelFile extends Model
{
    protected $fillable = [
        'file_name',
        'path',
        'driver',
        'status',
        'rows_extracted',
    ];

    protected $casts = [
        'status' => ExcelFileStatus::class,
        'rows_extracted' => 'integer',
    ];

    public function sheets(): HasMany
    {
        return $this->hasMany(ExcelSheet::class);
    }

    public function isPending(): bool
    {
        return $this->status === ExcelFileStatus::PENDING;
    }

    public function isReading(): bool
    {
        return $this->status === ExcelFileStatus::READING;
    }

    public function isReadyForProcessing(): bool
    {
        return $this->status === ExcelFileStatus::READ;
    }

    public function isProcessing(): bool
    {
        return $this->status === ExcelFileStatus::PROCESSING;
    }

    public function isCompleted(): bool
    {
        return $this->status === ExcelFileStatus::DONE;
    }

    public function isFailed(): bool
    {
        return $this->status === ExcelFileStatus::FAILED;
    }

    public function getResolvedPath(): string
    {
        return storage_path($this->path);
    }

    public function getReadableStatus(): string
    {
        return match ($this->status) {
            ExcelFileStatus::PENDING => 'Waiting to start',
            ExcelFileStatus::READING => 'Reading rows',
            ExcelFileStatus::READ => 'Extracted and ready',
            ExcelFileStatus::PROCESSING => 'Processing data',
            ExcelFileStatus::DONE => 'Completed successfully',
            ExcelFileStatus::FAILED => 'Failed to import',
        };
    }

    public function markAsFailed(string $reason = null): void
    {
        $this->update(['status' => ExcelFileStatus::FAILED]);
        if ($reason) {
            \Log::warning("Excel import failed [file ID: {$this->id}]: $reason");
        }
    }
}