## [v0.8.0] - 2025-09-06

### Added
- Introduced transformer and validator foundation allowing per-sheet and per-column callbacks.
- Implemented `ExcelRowError` model and persistence for rows failing validation or transformation.
- Enhanced `ProcessChunkJob` to handle errors and store them for later user correction.
- Updated chunk processing to be memory-friendly and idempotent.
- Added multi-language support for error messages.
- Improved event-driven architecture and job orchestration.

### Changed
- Replaced `PersistService` with `ExcelRowRepository` for row insertion and chunk index updates.
- Applied ENUMs for status fields across `ExcelFile`, `ExcelSheet`, `ExcelRow`, and `ExcelRowChunk`.
- Refactored `TransformService` and `ValidateService` for singleton-per-sheet usage in queue workers.
- Simplified transformer logic to avoid unnecessary JSON decoding (handled by model casts).

### Fixed
- Fixed test failures related to deterministic chunking and row indexing.
- Corrected idempotency issues in `ProcessChunkJob`.
- Resolved memory inefficiency in large-file processing.

---

## [v0.7.0] - 2025-08-28

### Added
- Event-driven architecture for Excel file, sheet, and row processing.
- `ChunkerService` for deterministic chunk creation.
- `ProcessChunkJob` to handle individual row chunks.
- Initial transformer and validator structure with callback support.
- Basic test coverage for chunking, row processing, and event dispatching.

### Changed
- Updated directory structure for services, repositories, and jobs to follow Laravel standards.

### Fixed
- Initial test adjustments for row indexing and unique constraints.

---

## [v0.6.0] - 2025-08-15

### Added
- `ExcelRowChunks` model and migration.
- Status tracking for file, sheet, row, and chunk levels.
- Queueable jobs for row chunk processing.
- Logging for chunk processing success and failure.
- Retry logic for failed jobs.

### Changed
- Refined migration schemas for `excel_rows` and `excel_sheets`.
- Introduced foreign key constraints for referential integrity.

### Fixed
- Corrected insertion logic for chunked rows to prevent overlaps.

## [v0.5.0] - 2025-07-27

### Added
- Reads and stores rows from the first discovered sheet immediately after metadata extraction.
- Introduced `insert_batch_size` config to control database batch inserts.
- Fires `RowsExtracted` event with inserted row count.
- Updated `HandleSheetsDiscovered` to trigger `RowExtractionService`.

## [v0.4.0] - 2025-07-21

### Added
- Auto discovery and DB persistence of Excel sheet metadata
- `HandleExcelUploaded` listener
- `SheetDiscoveryService` and `ExcelSheetRepository`
- Sheet metadata test coverage
