<?php

namespace Akbarjimi\ExcelImporter\DTOs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Carries Excel file metadata throughout services.
 * Used for events, repository inserts, and adapters.
 */
final readonly class ExcelFileDTO implements Arrayable
{
    public function __construct(
        public string $fileName,
        public string $path,
        public string $driver = 'local'
    ) {}

    public function toArray(): array
    {
        return [
            'file_name' => $this->fileName,
            'path' => $this->path,
            'driver' => $this->driver,
        ];
    }
}
