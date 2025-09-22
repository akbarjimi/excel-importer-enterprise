<?php

use Akbarjimi\ExcelImporter\Enums\ExcelRowStatus;
use Akbarjimi\ExcelImporter\Events\EntitiesMapped;
use Akbarjimi\ExcelImporter\Jobs\MapChunkJob;
use Akbarjimi\ExcelImporter\Models\ExcelRow;
use Akbarjimi\ExcelImporter\Models\ExcelRowChunk;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Repositories\ExcelRowRepository;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Event::fake([EntitiesMapped::class]);
    Queue::fake();

    $this->sheet = ExcelSheet::factory()->create([
        'rows_count' => 3,
        'chunk_count' => 1,
        'processed_chunks' => 1,
    ]);

    $this->chunk = ExcelRowChunk::factory()->create([
        'excel_sheet_id' => $this->sheet->id,
        'from_row_id' => 1,
        'to_row_id' => 3,
        'size' => 3,
        'status' => 'completed',
        'mapping_status' => 'pending',
    ]);

    $this->rows = ExcelRow::factory()->count(3)->create([
        'excel_sheet_id' => $this->sheet->id,
        'status' => ExcelRowStatus::PROCESSED,
    ]);
});

it('maps rows via callback mapper and marks them as processed', function () {
    // Arrange
    config()->set("excel-importer-sheets.{$this->sheet->name}.mapper", function (array $payloads) {
        return array_map(fn($p) => [
            'name' => $p['A1'] ?? 'unknown',
            'email' => $p['B1'] ?? 'test@example.com',
        ], $payloads);
    });

    config()->set("excel-importer-sheets.{$this->sheet->name}.target_model", User::class);

    (new MapChunkJob($this->chunk->id))->handle(app(ExcelRowRepository::class));

    $this->chunk->refresh();
    $this->sheet->refresh();

    expect($this->chunk->mapping_status)->toBe('completed');
    expect($this->sheet->mapped_count)->toBe(3);
    expect($this->sheet->mapped_at)->not()->toBeNull();

    $mappedRows = ExcelRow::where('excel_sheet_id', $this->sheet->id)->get();
    $mappedRows->each(fn($r) => expect($r->status)->toBe(ExcelRowStatus::PROCESSED));

    Event::assertDispatched(EntitiesMapped::class);
});

it('is idempotent if already completed', function () {
    $this->chunk->update(['mapping_status' => 'completed']);

    (new MapChunkJob($this->chunk->id))->handle(app(ExcelRowRepository::class));

    $this->chunk->refresh();
    expect($this->chunk->mapping_status)->toBe('completed');
    Event::assertNotDispatched(EntitiesMapped::class);
});

it('fails gracefully and sets mapping_status failed on exception', function () {
    config()->set("excel-importer-sheets.{$this->sheet->name}.mapper", fn() => null);

    try {
        (new MapChunkJob($this->chunk->id))->handle(app(ExcelRowRepository::class));
    } catch (Throwable $e) {
        // ignored
    }

    $this->chunk->refresh();
    expect($this->chunk->mapping_status)->toBe('failed');
});
