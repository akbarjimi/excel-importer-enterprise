<?php

namespace Akbarjimi\ExcelImporter\DTOs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Represents a single row's raw content from a sheet.
 * Passed to RowRepository for insertion.
 */
final readonly class RowDTO implements Arrayable
{
    public function __construct(
        public int $sheetId,
        public array $content,
        public ?int $chunkIndex = null
    ) {}

    public function toArray(): array
    {
        return [
            'excel_sheet_id' => $this->sheetId,
            'content' => $this->content,
            'chunk_index' => $this->chunkIndex,
            'is_processed' => false,
        ];
    }
}
