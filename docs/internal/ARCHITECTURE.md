# Architecture

This document describes the high-level architecture of the Excel Importer package (v0.8.0).  
The system is **event-driven**, modular, and designed for scalability, memory efficiency, and error tolerance.

---

## Components

- **Models**
  - `ExcelFile` — represents an uploaded file.
  - `ExcelSheet` — represents a sheet within a file.
  - `ExcelRow` — represents a single row of data.
  - `ExcelRowChunk` — groups rows into chunks for processing.
  - `ExcelRowError` — stores validation/transform/system errors.

- **Repositories**
  - Abstract DB operations (`ExcelRowRepository`, `ExcelSheetRepository`).

- **Services**
  - `ImportManager` — orchestrates imports, fires initial events.
  - `SheetDiscoveryService` — discovers sheets in uploaded file.
  - `RowExtractionService` — extracts rows, stores in DB.
  - `ChunkerService` — splits rows into deterministic chunks.
  - `TransformService` — applies column transformers.
  - `ValidateService` — applies Laravel validators.

- **Jobs**
  - `ProcessChunkJob` — processes rows in a chunk, applies transform & validation.

- **Events**
  - `ExcelUploaded` — after upload.
  - `SheetsDiscovered` / `SheetDiscovered` — after sheet discovery.
  - `AllSheetsDispatched` — after all chunks dispatched.

- **Listeners**
  - `HandleExcelUploaded` — triggers sheet discovery.
  - `HandleSheetsDiscovered` — triggers row extraction.
  - `HandleSheetDiscovered` — dispatches chunk creation.
  - `HandleAllSheetsDispatched` — signals pipeline completion.

---

## Event Flow

```mermaid
sequenceDiagram
    participant U as User
    participant IM as ImportManager
    participant E as Events
    participant L as Listeners
    participant S as Services
    participant J as Jobs

    U->>IM: Upload Excel file
    IM->>E: ExcelUploaded
    E->>L: HandleExcelUploaded
    L->>S: SheetDiscoveryService.discover()

    S->>E: SheetsDiscovered
    E->>L: HandleSheetsDiscovered
    L->>S: RowExtractionService.extract()

    S->>E: SheetDiscovered
    E->>L: HandleSheetDiscovered
    L->>S: ChunkerService.chunk()

    S->>J: Dispatch ProcessChunkJob (per chunk)
    J->>S: TransformService.apply(row)
    J->>S: ValidateService.apply(row)
    alt Validation/Transform error
        J->>DB: Save ExcelRowError
    end

    J->>DB: Mark chunk completed
    J->>E: AllSheetsDispatched (when last chunk finishes)
