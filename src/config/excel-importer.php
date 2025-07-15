<?php

return [

    // Used to group rows for processing jobs
    'chunk_size' => env('EXCEL_IMPORTER_CHUNK_SIZE', 1000),

    // Default disk to resolve file paths from (ex: local, s3, etc.)
    'default_disk' => env('EXCEL_IMPORTER_DISK', 'local'),
];
