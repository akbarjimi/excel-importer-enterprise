<?php

namespace Akbarjimi\ExcelImporter\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ExcelRowError extends Model implements Arrayable
{
    protected $fillable = [
        'excel_row_id',
        'field',
        'error_type',
        'error_code',
        'message',
    ];

    protected $casts = [
        'field' => 'string',
        'error_type' => 'string',
        'error_code' => 'string',
        'message' => 'string',
    ];

    public function row(): BelongsTo
    {
        return $this->belongsTo(ExcelRow::class, 'excel_row_id');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'excel_row_id' => $this->excel_row_id,
            'field' => $this->field,
            'error_type' => $this->error_type,
            'error_code' => $this->error_code,
            'message' => $this->message,
            'created_at' => $this->created_at,
        ];
    }
}
