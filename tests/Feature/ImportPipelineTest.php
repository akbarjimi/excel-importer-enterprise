<?php

use Akbarjimi\ExcelImporter\Enums\ExcelFileStatus;
use Akbarjimi\ExcelImporter\Enums\ExcelSheetStatus;
use Akbarjimi\ExcelImporter\Events\ExcelUploaded;
use Akbarjimi\ExcelImporter\Events\SheetsDiscovered;
use Akbarjimi\ExcelImporter\Models\ExcelFile;
use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Services\ImportManager;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->stubFileName = '1sheet3rows1header.xlsx';

    $this->sourcePath = __DIR__ . '/../stubs/' . $this->stubFileName;

    $this->relativeTargetPath = 'testing/' . $this->stubFileName;
    $this->absoluteTargetPath = storage_path($this->relativeTargetPath);

    if (!is_dir(dirname($this->absoluteTargetPath))) {
        mkdir(dirname($this->absoluteTargetPath), 0777, true);
    }

    copy($this->sourcePath, $this->absoluteTargetPath);

    expect(file_exists($this->absoluteTargetPath))
        ->toBeTrue("Failed to copy test Excel file into storage: {$this->absoluteTargetPath}");
});

it('stores Excel file metadata in database', function () {
    Event::fake([ExcelUploaded::class]);

    $manager = app(ImportManager::class);

    $file = $manager->import($this->relativeTargetPath);

    expect($file)
        ->toBeInstanceOf(ExcelFile::class)
        ->file_name->toBe($this->stubFileName)
        ->path->toBe($this->relativeTargetPath)
        ->status->toBe(ExcelFileStatus::PENDING);

    assertDatabaseHas('excel_files', [
        'id' => $file->id,
        'path' => $this->relativeTargetPath,
        'file_name' => $this->stubFileName,
    ]);

    Event::assertDispatched(ExcelUploaded::class, fn($event) => $event->file->id === $file->id);
});

// ─────────────────────────────────────────────────────────────
// ✅ Test 2: Excel sheet metadata is stored after file upload
// ─────────────────────────────────────────────────────────────

it('stores Excel sheet metadata in database after file is uploaded', function () {
    Event::fake([SheetsDiscovered::class]);
    /** @var ImportManager $manager */
    $manager = app(ImportManager::class);

    dump("📥 Running import pipeline for: {$this->relativeTargetPath}");

    $file = $manager->import($this->relativeTargetPath);

    dump("🧾 File import finished. File ID: {$file->id}");

    $sheets = ExcelSheet::where('excel_file_id', $file->id)->get();

    dump("🧾 Sheet records fetched from DB: {$sheets->count()}");

    expect($sheets)
        ->not->toBeEmpty()
        ->count()->toBeGreaterThan(0);

    $firstSheet = $sheets->first();

    dump([
        '🧾 First Sheet Name' => $firstSheet->name,
        'Rows Count' => $firstSheet->rows_count,
        'Status' => $firstSheet->status,
    ]);

    expect($firstSheet->name)->toBeString()->not->toBeEmpty();
    expect($firstSheet->rows_count)->toBeGreaterThan(0);
    expect($firstSheet->status)->toBe(ExcelSheetStatus::PENDING->value);
});
