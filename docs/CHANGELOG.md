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
