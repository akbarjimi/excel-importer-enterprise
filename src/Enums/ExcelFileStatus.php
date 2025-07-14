<?php

namespace Akbarjimi\ExcelImporter\Enums;

enum ExcelFileStatus: string
{
    case PENDING = 'pending';
    case READING = 'reading';
    case READ = 'read';
    case PROCESSING = 'processing';
    case DONE = 'done';
    case FAILED = 'failed';
}
