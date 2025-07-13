<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Chunk Size for Row Extraction and Processing
    |--------------------------------------------------------------------------
    |
    | The number of rows to extract or process per batch (chunk).
    | Tuning this value affects performance and memory usage.
    |
    */
    'chunk_size' => env('EXCEL_IMPORTER_CHUNK_SIZE', 1000),

    /*
    |--------------------------------------------------------------------------
    | Temporary Storage Path
    |--------------------------------------------------------------------------
    |
    | Directory where temporary Excel files are stored before processing.
    | This path must be writable and should not be publicly accessible.
    |
    */
    'tmp_path' => env('EXCEL_IMPORTER_TMP_PATH', storage_path('app/tmp')),

    /*
    |--------------------------------------------------------------------------
    | Queue Jobs
    |--------------------------------------------------------------------------
    |
    | Whether event listeners and jobs should be dispatched to the queue.
    | Set to false for synchronous development/test execution.
    |
    */
    'queue_jobs' => env('EXCEL_IMPORTER_QUEUE_JOBS', true),

    /*
    |--------------------------------------------------------------------------
    | Enable Chunking for Row Insertion
    |--------------------------------------------------------------------------
    |
    | If true, rows will be inserted in bulk chunks to optimize performance.
    | Highly recommended for large files with thousands of rows.
    |
    */
    'use_chunk_insert' => env('EXCEL_IMPORTER_USE_CHUNK_INSERT', true),

    /*
    |--------------------------------------------------------------------------
    | Default Disk for Excel Files
    |--------------------------------------------------------------------------
    |
    | The default filesystem disk used to access stored Excel files.
    | Must be defined in config/filesystems.php.
    |
    */
    'default_disk' => env('EXCEL_IMPORTER_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Sheet Configuration Path
    |--------------------------------------------------------------------------
    |
    | Directory where per-sheet config files live (map + validate logic).
    | You can cache these files in production for performance.
    |
    */
    'sheet_config_path' => base_path('excel-sheet-configs'),

    /*
    |--------------------------------------------------------------------------
    | Sheet Configuration Cache Key
    |--------------------------------------------------------------------------
    |
    | Cache key used for storing sheet configuration mapping rules.
    | You can invalidate this when deploying new sheet types.
    |
    */
    'sheet_config_cache_key' => 'excel_importer_sheet_configs',

    /*
    |--------------------------------------------------------------------------
    | File Status States
    |--------------------------------------------------------------------------
    |
    | Enum-style states used for tracking file lifecycle stages.
    | These should match the DB enum column values.
    |
    */
    'statuses' => [
        'pending',
        'reading',
        'read',
        'processing',
        'processed',
        'failed',
    ],
];
