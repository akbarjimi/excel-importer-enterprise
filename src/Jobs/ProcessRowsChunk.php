<?php

namespace Akbarjimi\ExcelImporter\Jobs;

use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Services\SheetRowMapper;
use Akbarjimi\ExcelImporter\Services\SheetRowValidator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessRowsChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $sheetId,
        public readonly int $chunkIndex,
        public readonly int $chunkSize = 1000
    ) {}

    public function handle(SheetRowValidator $validator, SheetRowMapper $mapper): void
    {
        try {
            $sheet = ExcelSheet::findOrFail($this->sheetId);
            $query = $sheet->rows()->orderBy('id')->skip($this->chunkIndex * $this->chunkSize)->take($this->chunkSize);

            foreach ($query->cursor() as $row) {
                $data = json_decode($row->content, true);

                $errors = $validator->validate($sheet, $data);
                if ($errors) {
                    $row->errors()->create(['messages' => $errors]);
                }
            }

        } catch (Throwable $e) {
            Log::error("Failed to process chunk [sheet: {$this->sheetId}, chunk: {$this->chunkIndex}]: {$e->getMessage()}");
        }
    }
}
