# Product Requirements Document (PRD) - Excel Importer Package

## Product Overview
The Excel Importer Package is an event-driven Laravel package designed to efficiently process large Excel files. It supports multi-sheet files, per-sheet row chunking, transformation, validation, and error tracking. The package is fully distributable, memory-efficient, and provides clear logs and status tracking.

## Goals
- Enable developers to import Excel files of arbitrary size without overwhelming memory.
- Allow per-sheet and per-column transformation and validation using a flexible callback system.
- Track and store errors in rows for user correction and reprocessing.
- Provide an event-driven architecture with job orchestration and retry capabilities.
- Support multiple languages for error messages and logs.

## Target Users
- Backend developers using Laravel.
- Teams needing a reliable, scalable, and distributable Excel import solution.
- Developers requiring integration with job queues and event-driven architectures.

## Features

### Core Features
1. **Excel File Handling**
   - Import Excel files with multiple sheets.
   - Store file metadata (`file_name`, `path`, `driver`, `status`, timestamps, owner).

2. **Sheet Handling**
   - Discover and store sheets.
   - Track sheet status, row count, chunk count, extraction timestamps.

3. **Row Processing**
   - Store rows as JSON content.
   - Track row status, row index, chunk index, content hash.
   - Apply transformers and validators per sheet and per column.

4. **Transformers & Validators**
   - Register sheet-specific transformers and validators.
   - Support callback-based transformations.
   - Validate rows using Laravel's built-in validation system.
   - Error rows stored in `ExcelRowError` for later correction.

5. **Chunking & Job Orchestration**
   - Break sheets into row chunks for efficient processing.
   - Dispatch jobs for each chunk with idempotency and retries.
   - Track chunk status and processing attempts.
   - Logging for success, failure, and dispatched jobs.

6. **Error Handling**
   - Capture transformation and validation errors.
   - Store errors with field, type, code, message, and timestamps.
   - Support multi-language error messages.

### Non-Functional Requirements
- Memory-efficient processing with cursor-based row retrieval.
- Fully event-driven architecture.
- Distributable and maintainable package following SOLID principles.
- Configurable per-sheet and per-column transformers and validators.
- Logging for debugging and auditing.

## Constraints
- Compatible with Laravel 10+ and Queue Workers.
- Use ENUMs for all status tracking.
- Should not require Redis or external caching for MVP.
- Support multiple Excel engines (e.g., Maatwebsite, Spout).

## Success Metrics
- Ability to process large Excel files (>100k rows) without memory exhaustion.
- Accurate transformation and validation per sheet and per column.
- All tests passing on feature and integration levels.
- User-friendly error correction workflow.
