<?php

return [

    'chunk_size' => env('EXCEL_IMPORTER_CHUNK_SIZE', 1000),

    'tmp_path' => env('EXCEL_IMPORTER_TMP_PATH', storage_path('app/tmp')),

    'queue_jobs' => env('EXCEL_IMPORTER_QUEUE_JOBS', true),

    'use_chunk_insert' => env('EXCEL_IMPORTER_USE_CHUNK_INSERT', true),

    'default_disk' => env('EXCEL_IMPORTER_DISK', 'local'),

    'sheet_config_path' => env('EXCEL_IMPORTER_SHEET_CONFIG_PATH', base_path('excel-sheet-configs')),

    'sheet_config_cache_key' => env('EXCEL_IMPORTER_SHEET_CONFIG_CACHE_KEY', 'excel_importer_sheet_configs'),

];
