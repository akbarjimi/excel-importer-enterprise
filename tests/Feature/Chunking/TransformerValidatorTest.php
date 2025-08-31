<?php

use Akbarjimi\ExcelImporter\Models\ExcelSheet;
use Akbarjimi\ExcelImporter\Services\TransformService;
use Akbarjimi\ExcelImporter\Services\ValidateService;

it('applies transformer and validation correctly', function () {
    // Arrange
    $sheet = new ExcelSheet(['name' => 'Sheet1']);
    $rowContent = ['A1' => 'hello', 'B1' => ' 123 '];

    $transform = new TransformService();
    $validate = new ValidateService();

    $transform->load($sheet);
    $validate->load($sheet);

    // Act
    $transformed = $transform->apply($rowContent);
    $errors = $validate->apply($transformed);

    // Assert
    expect($transformed['A1'])->toBe('HELLO');
    expect($transformed['B1'])->toBe('123');
    expect($errors)->toBeEmpty();

    // Introduce validation error
    $invalidRow = ['A1' => '', 'B1' => 'abc'];
    $errors = $validate->apply($invalidRow);

    expect($errors)->not()->toBeEmpty();
    expect($errors)->toHaveKeys(['A1', 'B1']);
});
