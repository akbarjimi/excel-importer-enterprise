<?php

namespace Akbarjimi\ExcelImporter\DTOs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Data Transfer Object representing a discovered Excel sheet.
 * Created during SheetDiscoveryService::discover(), passed through
 * event pipeline, and used by repository to persist sheet metadata.
 */
final readonly class SheetDTO implements Arrayable
{
    public function __construct(
        public string $name,
        public int $index,
        public int $rowsCount,
        public int $fileId,
        public array $meta = [],
    ) {}

    public static function fromAdapter(array $adapterSheetPayload, int $fileId): self
    {
        return new self(
            name: $adapterSheetPayload['name'],
            index: $adapterSheetPayload['index'],
            rowsCount: $adapterSheetPayload['rows_count'] ?? 0,
            fileId: $fileId,
            meta: $adapterSheetPayload['meta'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'index' => $this->index,
            'rows_count' => $this->rowsCount,
            'excel_file_id' => $this->fileId,
            'meta' => $this->meta,
        ];
    }
}
