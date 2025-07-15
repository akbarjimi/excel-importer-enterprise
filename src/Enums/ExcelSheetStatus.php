<?php

namespace Akbarjimi\ExcelImporter\Enums;

enum ExcelSheetStatus: string
{
    case PENDING = 'pending';                       // Sheet discovered, no action yet
    case EXTRACTING = 'extracting';                 // Sheet being parsed
    case EXTRACTED = 'extracted';                   // Rows extracted
    case CHUNKS_DISPATCHED = 'chunks_dispatched';   // Ready for chunked processing
    case COMPLETED = 'completed';                   // All rows processed
    case FAILED = 'failed';                         // Sheet processing failed
}
