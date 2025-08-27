<?php

use Akbarjimi\ExcelImporter\Jobs\ProcessChunkJob;
use Akbarjimi\ExcelImporter\Models\ExcelRowChunk;
use Akbarjimi\ExcelImporter\Models\ExcelRow;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Repositories\ExcelRowRepository;
use Akbarjimi\ExcelImporter\Services\TransformService;
use Akbarjimi\ExcelImporter\Services\ValidateService;

it('processes a chunk idempotently', function () {
    $sheet = ExcelSheet::factory()->create();
    $rows  = ExcelRow::factory()->count(5)->for($sheet)->create();

    $chunk = ExcelRowChunk::create([
        'excel_sheet_id' => $sheet->getKey(),
        'from_row_id'    => $rows->first()->getKey(),
        'to_row_id'      => $rows->last()->getKey(),
        'size'           => 5,
        'status'         => 'pending',
    ]);

    $job = app(ProcessChunkJob::class, ['chunkId' => $chunk->getKey()]);
    $job->handle(app(TransformService::class), app(ValidateService::class), app(ExcelRowRepository::class));
    $job->handle(app(TransformService::class), app(ValidateService::class), app(ExcelRowRepository::class));

    expect($chunk->fresh()->status)->toBe('completed');
});
