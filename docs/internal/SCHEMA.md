```mermaid
erDiagram

    excel_files {
        bigint id PK
        string file_name
        string path
        string driver
        enum status
        timestamp extracted_at
        timestamp processed_at
        timestamp failed_at
        json meta
        text exception
        bigint owner_id
        string owner_type
        timestamps
    }

    excel_sheets {
        bigint id PK
        bigint excel_file_id FK
        string name
        enum status
        int rows_count
        timestamp rows_extracted_at
        int chunk_count
        json meta
        text exception
        timestamps
    }

    excel_rows {
        bigint id PK
        bigint excel_sheet_id FK
        int row_index
        json content
        string hash_algo
        string content_hash
        enum status
        int chunk_index
        timestamps
    }

    excel_row_errors {
        bigint id PK
        bigint excel_row_id FK
        string field
        string error_type
        string error_code
        text message
        timestamps
    }

    excel_row_chunks {
        bigint id PK
        bigint excel_sheet_id FK
        bigint from_row_id
        bigint to_row_id
        int size
        enum status
        int attempts
        text error
        timestamp dispatched_at
        timestamp processed_at
        timestamps
    }

    %% Relationships
    excel_files ||--o{ excel_sheets : "has many"
    excel_sheets ||--o{ excel_rows : "has many"
    excel_rows ||--o{ excel_row_errors : "has many"
    excel_sheets ||--o{ excel_row_chunks : "has many"

    %% Chunks reference ranges of rows
    excel_row_chunks }o--|| excel_rows : "from_row_id/to_row_id"