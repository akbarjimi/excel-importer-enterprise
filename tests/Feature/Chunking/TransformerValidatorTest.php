<?php

use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Services\TransformService;
use Akbarjimi\ExcelImporter\Services\ValidateService;

beforeEach(function () {
    Config::set('excel-importer-sheets', require_once __DIR__ . "/../../_fixtures/config/excel-importer-sheets.php");
});

it('applies transformer correctly', function () {
    $sheet = ExcelSheet::factory()->make(['name' => 'Sheet1']);
    $rowContent = ['A1' => 'hello', 'B1' => '123'];

    $transform = app(TransformService::class);
    $transform->load($sheet);

    $transformed = $transform->apply($rowContent);

    expect($transformed['A1'])->toBe('HELLO');
    expect($transformed['B1'])->toBe('123');
});

it('applies validator correctly for valid rows', function () {
    $sheet = ExcelSheet::factory()->make(['name' => 'Sheet1']);
    $validRow = ['A1' => 'HELLO', 'B1' => 'john.doe@mail.com', 'C1' => 31,];

    $validate = app(ValidateService::class);
    $validate->load($sheet);

    $errorsValid = $validate->apply($validRow);

    expect($errorsValid)->toBeEmpty();
});

it('applies validator correctly for invalid rows', function () {
    $sheet = ExcelSheet::factory()->make(['name' => 'Sheet1']);
    $invalidRow = ['A1' => '', 'B1' => 'john.doe@mail.com', 'C1' => '30',];

    $validate = app(ValidateService::class);
    $validate->load($sheet);

    $errorsInvalid = $validate->apply($invalidRow);

    expect($errorsInvalid)->not()->toBeEmpty();
    expect($errorsInvalid)->toHaveKeys(['A1', 'C1']);
});
