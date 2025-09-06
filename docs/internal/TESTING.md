# Testing Documentation

This document outlines the testing strategy, coverage, and specific tests for the Excel Importer package. It is designed to help developers understand what is being tested, how, and which parts of the pipeline are covered.

---

## Testing Strategy

- **Framework:** PestPHP (`^3.8`) with Orchestra Testbench (`^10.2`) for Laravel integration.
- **Types of Tests:**
  - **Unit Tests:** Test individual classes and services in isolation.
  - **Feature Tests:** Test specific features or jobs with realistic inputs.
  - **Integration Tests:** Validate the end-to-end workflow, including multiple components interacting.
- **Goals:**
  - Ensure event-driven workflow correctness.
  - Validate transformer and validator logic.
  - Ensure chunking and row processing handle large files correctly.
  - Ensure idempotency and error handling.

---

## Unit Tests

### `tests/Unit/ImportManagerTest.php`

- **Purpose:** Verify that `ImportManager` stores metadata and dispatches `ExcelUploaded` event correctly.
- **Coverage:** Excel file and sheet metadata insertion, event dispatching.
- **Notes:** Uses factories and mocks to isolate `ImportManager` behavior.

---

## Feature Tests

### `tests/Feature/Chunking/ChunkerServiceTest.php`

- **Purpose:** Test deterministic chunk creation and job dispatch after transaction commit.
- **Coverage:** `ChunkerService` logic for dividing sheets into chunks and dispatching `ProcessChunkJob`.
- **Assertions:** Chunks are created with correct ranges, jobs are dispatched post-commit.

### `tests/Feature/Chunking/ProcessChunkJobTest.php`

- **Purpose:** Test idempotent processing of chunked rows.
- **Coverage:** `ProcessChunkJob` ensures rows are processed once; handles retries and logs errors.
- **Assertions:** Processed status, error handling, logging.

### `tests/Feature/Chunking/TransformerValidatorTest.php`

- **Purpose:** Validate transformer and validator logic.
- **Coverage:** Correct transformation of row data, validation of valid rows, detection of invalid rows.
- **Assertions:** Transformed values, validation error messages.

### `tests/Feature/ImportPipelineTest.php`

- **Purpose:** Test end-to-end pipeline from Excel file upload to row processing.
- **Coverage:**
  - Storing file and sheet metadata
  - Dispatching sheet events
  - Row extraction and `AllSheetsDispatched` firing
- **Assertions:** Database records, events dispatched, chunking consistency.

---

## Integration Tests

### `tests/Integration/ExampleIntegrationTest.php`

- **Purpose:** Ensure at least one integration test verifies the system wiring.
- **Coverage:** Minimal check of the workflow integration.

---

## Notes

- **Fixtures and Stubs:** All Excel files used in tests are located under `tests/_stubs`.
- **Error Scenarios:** Some feature tests include invalid data to check error logging and row rejection.
- **Idempotency:** All chunk processing is tested to ensure jobs can safely retry.
- **Environment:** Tests run in an isolated database using in-memory SQLite where possible.

---

## Recommendations

- Run tests after any change to chunking, transformer, or validator logic.
- Extend integration tests to cover multi-sheet, multi-engine scenarios.
- Monitor memory usage and number of queries during large-file tests.
- Keep tests small, fast, and isolated to ensure quick feedback during development.
