```mermaid
erDiagram
    excel_files {
        bigint id PK "Unique identifier for the file record"
        string file_name "The original name of the uploaded Excel file"
        string path "The storage path of the file"
        string driver "The specific parsing logic or driver used for this file type"
        enum status "The processing status of the file (e.g., pending, processing, processed, failed)"
        timestamp extracted_at "When the file was initially parsed and data extracted"
        timestamp processed_at "When processing of the file and its sheets was completed successfully"
        timestamp failed_at "When file processing failed"
        json meta "General metadata about the file in JSON format"
        text exception "Detailed error message if processing fails"
        bigint owner_id "The ID of the owner of the file (e.g., a user or project)"
        string owner_type "The type of the owner (e.g., 'user', 'project')"
        timestamp created_at "Timestamp of when the record was created"
        timestamp updated_at "Timestamp of the last update to the record"
    }

    excel_sheets {
        bigint id PK "Unique identifier for the sheet record"
        bigint excel_file_id FK "Foreign key linking to the parent file"
        string name "The name of the sheet within the Excel file"
        enum status "The processing status of this specific sheet"
        int rows_count "The total number of rows found in the sheet"
        timestamp rows_extracted_at "When the rows from this sheet were extracted"
        int chunk_count "The number of processing chunks created for this sheet"
        json meta "Metadata specific to this sheet"
        text exception "Detailed error message if sheet processing fails"
        timestamp created_at "Timestamp of when the record was created"
        timestamp updated_at "Timestamp of the last update to the record"
    }

    excel_rows {
        bigint id PK "Unique identifier for the row record"
        bigint excel_sheet_id FK "Foreign key linking to the parent sheet"
        int row_index "The original row number within the sheet"
        json content "The data of the row, stored as JSON"
        string hash_algo "The algorithm used to create the content hash (e.g., SHA256)"
        string content_hash "A hash of the row's content for deduplication or integrity checks"
        enum status "The processing status of the individual row"
        int chunk_index "The index of the chunk this row belongs to"
        timestamp created_at "Timestamp of when the record was created"
        timestamp updated_at "Timestamp of the last update to the record"
    }

    excel_row_errors {
        bigint id PK "Unique identifier for the error record"
        bigint excel_row_id FK "Foreign key linking to the specific row that has the error"
        string field "The name of the column or field that caused the error"
        string error_type "A classification of the error (e.g., 'validation', 'format')"
        string error_code "A specific code for the error type"
        text message "A human-readable message describing the error"
        timestamp created_at "Timestamp of when the record was created"
        timestamp updated_at "Timestamp of the last update to the record"
    }

    excel_row_chunks {
        bigint id PK "Unique identifier for the chunk record"
        bigint excel_sheet_id FK "Foreign key linking to the parent sheet"
        bigint from_row_id "The ID of the first row in this chunk"
        bigint to_row_id "The ID of the last row in this chunk"
        int size "The total number of rows in this chunk"
        enum status "The processing status of the chunk"
        int attempts "The number of times processing has been attempted for this chunk"
        text error "Detailed error message if chunk processing fails"
        timestamp dispatched_at "When the chunk was sent for processing"
        timestamp processed_at "When the chunk processing was completed"
        timestamp created_at "Timestamp of when the record was created"
        timestamp updated_at "Timestamp of the last update to the record"
    }

    %% Relationships
    excel_files ||--o{ excel_sheets : "has many"
    excel_sheets ||--o{ excel_rows : "has many"
    excel_sheets ||--o{ excel_row_chunks : "has many"
    excel_rows ||--o{ excel_row_errors : "has many"

    %% Chunks reference ranges of rows
    excel_row_chunks }o--|| excel_rows : "from_row_id/to_row_id"
