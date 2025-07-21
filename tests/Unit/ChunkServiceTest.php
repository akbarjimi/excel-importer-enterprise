<?php

use Akbarjimi\ExcelImporter\Events\ProcessRowsChunk;
use Akbarjimi\ExcelImporter\Models\ExcelRow;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Services\ChunkService;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();
});

it('does not dispatch chunks for empty sheet', function () {
    $sheet = ExcelSheet::factory()->create();

    app(ChunkService::class)->splitSheetIntoChunks($sheet);

    Event::assertNotDispatched(ProcessRowsChunk::class);
});

it('dispatches chunk events based on row count and chunk size', function () {
    $sheet = ExcelSheet::factory()->create();

    ExcelRow::factory()->count(2500)->create([
        'excel_sheet_id' => $sheet->id,
    ]);

    config()->set('excel-importer.chunk_size', 1000);

    app(ChunkService::class)->splitSheetIntoChunks($sheet);

    Event::assertDispatchedTimes(ProcessRowsChunk::class, 3);

    Event::assertDispatched(ProcessRowsChunk::class, function ($event) use ($sheet) {
        return $event->sheetId === $sheet->id && $event->chunkIndex === 0;
    });

    Event::assertDispatched(ProcessRowsChunk::class, function ($event) {
        return $event->chunkIndex === 2;
    });
});
