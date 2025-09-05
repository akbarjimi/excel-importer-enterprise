<?php

return [
    // 1sheet3rows1header.xlsx
    'Sheet1' => [
        'transformers' => [
            'A1' => fn ($value) => strtoupper($value),
            'B1' => fn ($value) => strtoupper($value),
            'C1' => fn ($value) => strtoupper($value),
        ],
        'validation' => [
            'A1' => 'required|string|max:255',
            'B1' => 'required|string|email',
            'C1' => 'required|int|min:31',
        ],
    ],
    // 2sheets2rows.xlsx
    'Sheet2' => [
        'transformers' => [
            'A1' => fn ($value) => intval($value),
            'B1' => fn ($value) => intval($value),
        ],
        'validation' => [
            'A1' => 'required|int',
            'B1' => 'required|int',
        ],
    ],
    'Sheet3' => [
        'transformers' => [
            'A1' => fn ($value) => intval($value),
            'B1' => fn ($value) => intval($value),
        ],
        'validation' => [
            'A1' => 'required|int',
            'B1' => 'required|int',
        ],
    ],
    // 2sheets2000rows.xlsx
    'Sheet4' => [
        'transformers' => [
            'A1' => fn ($value) => intval($value),
        ],
        'validation' => [
            'A1' => 'required|int|max:2000',
        ],
    ],
    'Sheet5' => [
        'transformers' => [
            'A1' => fn ($value) => intval($value),
        ],
        'validation' => [
            'A1' => 'required|int|max:2000',
        ],
    ],
];
