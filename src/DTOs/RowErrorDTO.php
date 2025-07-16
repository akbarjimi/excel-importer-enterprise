<?php

namespace Akbarjimi\ExcelImporter\DTOs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Represents a row-level validation or transformation error.
 * Passed to RowErrorRepository for recording.
 */
final readonly class RowErrorDTO implements Arrayable
{
    public function __construct(
        public int $rowId,
        public array $messages
    ) {}

    public function toArray(): array
    {
        return [
            'excel_row_id' => $this->rowId,
            'messages' => $this->messages,
        ];
    }
}
