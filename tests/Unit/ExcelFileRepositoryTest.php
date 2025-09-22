<?php

use Akbarjimi\ExcelImporter\Repositories\ExcelFileRepository;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

beforeEach(function () {
    $this->file = ExcelFile::factory()->create();
});

it('increments processed_chunks and detects completion', function () {
    $sheet = ExcelSheet::factory()->for($this->file)->create([
        'chunk_count' => 2,
        'processed_chunks' => 1,
    ]);

    $repo = new ExcelFileRepository();

    $new = $repo->incrementProcessedChunks($sheet->id);
    expect($new)->toBe(2);

    $allDone = $repo->allChunksProcessed($sheet->id);
    expect($allDone)->toBeTrue();
});

it('sets chunk_count and reads back value', function () {
    $sheet = ExcelSheet::factory()->for($this->file)->create([
        'chunk_count' => 0,
        'processed_chunks' => 0,
    ]);

    $repo = new ExcelFileRepository();
    $repo->setSheetChunkCount($sheet->id, 5);

    $fresh = ExcelSheet::find($sheet->id);
    expect($fresh->chunk_count)->toBe(5);
});

it('increments mapped_count and sets mapped_at when complete', function () {
    $sheet = ExcelSheet::factory()->for($this->file)->create([
        'rows_count' => 3,
        'mapped_count' => 0,
    ]);

    $repo = new ExcelFileRepository();
    $count = $repo->incrementMappedCount($sheet->id, 3);
    expect($count)->toBe(3);

    $repo->markSheetMappedAtIfComplete($sheet->id);
    $fresh = ExcelSheet::find($sheet->id);
    expect($fresh->mapped_at)->not()->toBeNull();
});
