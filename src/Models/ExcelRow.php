<?php

namespace Akbarjimi\ExcelImporter\Models;

use Akbarjimi\ExcelImporter\Enums\ExcelRowStatus;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class ExcelRow extends Model implements Arrayable
{
    use HasFactory;

    protected $fillable = [
        'excel_sheet_id',
        'row_index',
        'content',
        'content_hash',
        'status',
        'chunk_index',
    ];

    protected $casts = [
        'content' => 'array',
        'status' => ExcelRowStatus::class,
        'chunk_index' => 'integer',
        'row_index' => 'integer',
    ];

    // ------------------------------------------------------------------
    // Relationships
    // ------------------------------------------------------------------

    public function sheet(): BelongsTo
    {
        return $this->belongsTo(ExcelSheet::class);
    }

    // ------------------------------------------------------------------
    // Scopes
    // ------------------------------------------------------------------

    public function scopePending($query)
    {
        return $query->where('status', ExcelRowStatus::PENDING);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'like', 'failed_%');
    }

    // ------------------------------------------------------------------
    // Arrayable implementation
    // ------------------------------------------------------------------

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sheet_id' => $this->excel_sheet_id,
            'row_index' => $this->row_index,
            'content' => $this->content,
            'status' => $this->status->value,
            'chunk_index' => $this->chunk_index,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
