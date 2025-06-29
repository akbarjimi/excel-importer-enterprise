<?php

test('greet returns expected string', function () {
    $dummy = new \Akbarjimi\ExcelImporter\Dummy();

    expect($dummy->greet('World'))->toBe('Hello, World!');
});
