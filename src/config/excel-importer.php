<?php

return [

    'chunk_size' => env('EXCEL_IMPORTER_CHUNK_SIZE', 1000),

    'insert_batch_size' => env('EXCEL_IMPORTER_INSERT_BATCH_SIZE', 100),

    'default_disk' => env('EXCEL_IMPORTER_DISK', 'local'),
];
