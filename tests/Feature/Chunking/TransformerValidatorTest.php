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

it('applies validator correctly', function () {
    $sheet = ExcelSheet::factory()->make(['name' => 'Sheet1']);
    $validRow = ['A1' => 'HELLO', 'B1' => '123'];
    $invalidRow = ['A1' => '', 'B1' => 'abc'];

    $validate = app(ValidateService::class);
    $validate->load($sheet);

    $errorsValid = $validate->apply($validRow);
    $errorsInvalid = $validate->apply($invalidRow);

    expect($errorsValid)->toBeEmpty();
    expect($errorsInvalid)->not()->toBeEmpty();
    expect($errorsInvalid)->toHaveKeys(['A1', 'B1']);
});
