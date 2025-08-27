# Architecture Overview

## Flow Diagram
```
[Developer Uploads File] → [ImportExcelFileJob] → excel_files
                                   ↓
                        [DiscoverSheetsJob] → excel_sheets
                                   ↓
                        [ProcessSheetRowsJob (N)] → excel_rows
                                   ↓
                        [ProcessRowJob (N)] → row validation & mapping
```

## Components
- Controller: receives uploaded Excel file, validates, and stores it
- ExcelImporterService: service class to start import job chain
- Jobs: ImportExcelFileJob → DiscoverSheetsJob → ProcessSheetRowsJob → ProcessRowJob
- Models: ExcelFile, ExcelSheet, ExcelRow, ExcelError
- Events: later stages may emit lifecycle events for observers
- Config: controls chunk size, queue name, error storage toggle

## Tables
- `excel_files`: metadata
- `excel_sheets`: sheet info per file
- `excel_rows`: raw row data in JSON
- `excel_errors`: linked to rows
