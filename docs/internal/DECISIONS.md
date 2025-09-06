# Architecture Decision Records

This document logs major architectural decisions for the Excel Importer package.  
Format follows [ADR](https://cognitect.com/blog/2011/11/15/documenting-architecture-decisions).

---

## ADR 001: Event-driven architecture
**Date:** 2025-09-05  
**Status:** Accepted

### Context
The importer needs to process Excel files asynchronously and at scale. Direct procedural flows would couple services tightly and make it difficult to extend (e.g., adding new validators, parallel processing, or alternative engines).

### Decision
Adopt Laravel's native **event-driven pattern** (events + listeners + queued jobs) to orchestrate the import pipeline.

### Consequences
- **Positive:**  
  - Decoupling of services.  
  - Easy to add new steps in pipeline without breaking others.  
  - Fits Laravel ecosystem well (queues, events, jobs).  
- **Negative:**  
  - More moving parts to document.  
  - Debugging requires tracing event chains.  

---

## ADR 002: Chunk-based row processing
**Date:** 2025-09-05  
**Status:** Accepted

### Context
Excel files may contain millions of rows. Loading them all at once would exhaust memory and make the process unstable.

### Decision
Introduce **deterministic chunking** (configurable chunk size) and process each chunk via a queued job.

### Consequences
- **Positive:**  
  - Memory-friendly.  
  - Enables parallel processing across workers.  
  - Retries can be scoped to chunks.  
- **Negative:**  
  - Slightly higher complexity.  
  - Ordering must be handled deterministically.  

---

## ADR 003: Database-first metadata & error storage
**Date:** 2025-09-05  
**Status:** Accepted

### Context
Tracking progress and errors requires persistence. In-memory structures would not survive restarts or scale across multiple workers.

### Decision
Store metadata in DB tables (`excel_files`, `excel_sheets`, `excel_rows`, `excel_row_chunks`, `excel_row_errors`) instead of in-memory or logs only.

### Consequences
- **Positive:**  
  - Full traceability.  
  - Errors can be reviewed and reprocessed.  
  - Works with multiple workers in distributed environments.  
- **Negative:**  
  - More tables to maintain.  
  - Slight DB overhead.  

---

## ADR 004: JSON column storage for row data
**Date:** 2025-09-05  
**Status:** Accepted

### Context
Excel rows have flexible structures depending on sheet layout. Designing rigid relational schemas would force migrations and complex joins.

### Decision
Store row content as JSON in `excel_rows.content`.

### Consequences
- **Positive:**  
  - Flexible schema — no migrations needed for new columns.  
  - Faster development iteration.  
- **Negative:**  
  - Querying inside JSON is harder.  
  - Some DBs have weaker JSON support.  

---

## ADR 005: Config-driven transformers & validators
**Date:** 2025-09-05  
**Status:** Accepted

### Context
Each sheet may need custom business logic (transformers, validators). Hardcoding would make the system rigid.

### Decision
Use Laravel config (`excel-importer.php`, `excel-importer-sheets.php`) to register transformers and validators.

### Consequences
- **Positive:**  
  - Easy to extend without modifying core package.  
  - Per-sheet customization.  
- **Negative:**  
  - More complex setup for end-users.  
  - Misconfiguration can cause silent errors.  
