<?php

namespace Akbarjimi\ExcelImporter\DTOs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Represents metadata for a chunk of rows to be processed.
 * Used during chunking to dispatch chunk processing jobs.
 */
final readonly class ChunkMetaDTO implements Arrayable
{
    public function __construct(
        public int $sheetId,
        public int $chunkIndex,
        public int $offset,
        public int $limit
    ) {}

    public function toArray(): array
    {
        return [
            'sheet_id' => $this->sheetId,
            'chunk_index' => $this->chunkIndex,
            'offset' => $this->offset,
            'limit' => $this->limit,
        ];
    }
}
