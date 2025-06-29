# Product Requirements Document (PRD)

## Product Name
Laravel Excel Importer

## Problem
Each Laravel developer reinvents the wheel for Excel import: unreliable memory usage, poor error reporting, no retry, and unscalable. This package solves it with a distributed, testable, configurable Excel import pipeline.

## Target Users
Laravel developers and enterprise teams needing to import large Excel files with control and error traceability.

## Use Cases
- Import users in bulk
- Ingest financial records
- Migrate CRM data

## Goals for MVP
- Chunked row import
- Job-based queueing
- Logging failed rows
- Developer-provided file and path

## Out of Scope for MVP
- UI components
- CSV or JSON support
- Multilingual

## Technical Stack
- PHP 8.0
- Laravel 10 LTS
- maatwebsite/excel
- Queue system (database or Redis)
- pestphp + orchestra/testbench

## Success Metrics
- MVP: import small Excel file reliably
- Final: import 1M rows in <X min, with retries and full error trace