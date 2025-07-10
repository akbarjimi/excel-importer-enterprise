<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Chunk Size
    |--------------------------------------------------------------------------
    |
    | Number of rows per chunk when processing Excel rows asynchronously.
    |
    */
    'chunk_size' => 1000,

    /*
    |--------------------------------------------------------------------------
    | Temporary Storage Path
    |--------------------------------------------------------------------------
    |
    | Path where temporary Excel files are stored during import processing.
    |
    */
    'tmp_path' => storage_path('app/tmp'),

    /*
    |--------------------------------------------------------------------------
    | Queue Jobs Flag
    |--------------------------------------------------------------------------
    |
    | Whether to queue event listeners/jobs or process synchronously.
    | Set to true for production with queue workers.
    |
    */
    'queue_jobs' => env('EXCEL_IMPORTER_QUEUE_JOBS', true),

];
