<?php

use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Models\ExcelRow;
use Akbarjimi\ExcelImporter\Services\ChunkerService;
use Illuminate\Support\Facades\Bus;
use Akbarjimi\ExcelImporter\Jobs\ProcessChunkJob;

it('creates deterministic chunks and dispatches jobs after commit', function () {
    Bus::fake();

    $file  = ExcelFile::factory()->hasExcelSheets(2)->create();

    $sheets = $file->excelSheets;
    ExcelRow::factory()->count(1001)->for($sheets[0])->create();
    ExcelRow::factory()->count(1000)->for($sheets[1])->create();

    $chunks = app(ChunkerService::class, ['chunkSize' => 1000])
        ->createChunksForFile($file);

    expect($chunks)->toHaveCount(3);
    expect($chunks->pluck('size')->sort()->values()->all())->toBe([1,1000,1000]);

    $chunks->each(fn($c) => ProcessChunkJob::dispatch($c->getKey())->afterCommit());

    Bus::assertDispatched(ProcessChunkJob::class, 3);
});
