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
    | Make sure this path is writable and not publicly accessible.
    |
    */
    'tmp_path' => env('EXCEL_IMPORTER_TMP_PATH', storage_path('app/tmp')),

    /*
    |--------------------------------------------------------------------------
    | Queue Jobs
    |--------------------------------------------------------------------------
    |
    | Whether event listeners and jobs should be dispatched to the queue.
    | Set to false for local development or synchronous testing.
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
    | This value sets the default disk used for file reading.
    | Should match a disk defined in config/filesystems.php.
    |
    */
    'default_disk' => env('EXCEL_IMPORTER_DISK', 'local'),
];
