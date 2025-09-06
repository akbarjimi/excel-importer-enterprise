# Roadmap

This document outlines the development plan and milestones for the Excel Importer package.

---

## Version 0.8.0 (Current - In Progress)

**Goal:** Implement a robust, event-driven Excel import pipeline with transformers, validators, and chunked row processing.

**Completed:**
- Event-driven pipeline for file → sheet → row processing.
- Chunking system and `ExcelRowChunk` model.
- Transformer and Validator foundation (callback-based).
- Job orchestration and error handling.
- Tests for chunking, row processing, transformer/validator, and pipeline events.

**In Progress:**
- Storing row-level errors in `ExcelRowError`.
- Multi-language support for error messages.
- Singleton transformer and validator resolution per sheet in queue workers.
- Improving memory efficiency and logging.

**Next Steps / Priority:**
1. Complete row error handling and re-processing workflow.
2. Test larger Excel files (thousands of rows, multiple sheets).
3. Implement ENUMs for status tracking instead of strings.
4. Optimize database queries and memory usage.

---

## Version 0.9.0 (Near Future)

**Goals:**
- Add support for multiple Excel engines (e.g., Maatwebsite, Spout).
- Extend transformer system to allow OOP-style pipelines.
- Add configuration to publish transformer/validator registration file separately from tests.
- Improve documentation with examples and flow diagrams.
- Add integration tests for multi-sheet, multi-engine workflows.

---

## Version 1.0.0 (Stable Release)

**Goals:**
- Fully stable, production-ready package.
- Well-documented API and examples for developers.
- Complete coverage of common validation and transformation scenarios.
- Support for high-performance, memory-efficient large Excel files.
- Robust retry and error-handling mechanism.

---

## Future Ideas

- Web UI for monitoring import progress and errors.
- Support for incremental updates / partial imports.
- Analytics and reporting for processed data.
- Extend package to support CSV and other tabular formats.
