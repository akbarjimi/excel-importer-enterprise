## Roadmap

| Version | Goal (short) | Weight (impact) | Key deliverables |
| --- | --- | --- | --- |
| v0.9.0 | **Entity Mapper** (mapping staging → domain) | 4   | *   Design `EntityMapper` contract (callback & class-based).<br>*   Implement `MapEntitiesJob` dispatched per chunk (batch-aware, idempotent).<br>*   Add statuses: `integrated`, `failed_mapping`; update state machine + tests. |
| v0.10.0 | **Error-tolerant file reading** (robust streaming) | 5   | *   Abstract file driver (local, s3/minio) via `StorageAdapter`.<br>*   Implement streaming read strategies (temp local streaming + generator/cursor) and resume on network fault.<br>*   Wrapper layers: checksum, byte-range retries, exponential backoff, resume markers. |
| v0.12.0 | **Logical error tracking** (business-rule errors) | 3   | *   Define error taxonomy: validation vs transformation vs mapping vs system.<br>*   Store structured logical errors in `excel_row_errors` (field, code, severity, locale messages).<br>*   Expose export endpoint / CSV for corrected rows. |
| v0.13.0 | **Service unit tests** | 4   | *   Identify all services and add isolated unit tests with strict contracts & mocks (TransformService, ValidateService, ChunkerService, RowExtractionService, ImportManager, ExcelRowRepository). |
| v0.14.0 | **Feature tests** (end-to-end feature scenarios) | 4   | *   Write feature tests for: upload→extract→chunk→map, reprocess corrected rows, error-export-import flow. |
| v0.15.0 | **Integration & integrity tests** | 3   | *   Large-file integration (2sheets2000rows and larger), DB invariants, idempotency under retries. |
| v0.16.0 | **i18n / multilingual support** | 3   | *   Localize row errors, logs, and user-visible messages using Laravel Lang files; allow per-file locale override. |
| v0.17.0 | **Multi-engine support** (Maatwebsite, Spout) | 5   | *   Define `ExcelEngineInterface`, implement adapters for Maatwebsite & Spout (streaming-friendly). |
| v0.11.0 | **Docs refresh (continuous)** | 2   | *   Keep docs updated in parallel — prioritize TESTING.md, USAGE.md, examples for entity mapping and reprocessing. |
| v0.18.0 | **Composer granularity research** (investigate partial installations / tree-shaking) | 5 (research + infra) | *   Prototype minimal adapters that depend only on required sub-packages; open an RFC/PR to Composer ecosystem if viable.<br>*   This is a long-term, high-effort, high-reward objective. |

- - -
