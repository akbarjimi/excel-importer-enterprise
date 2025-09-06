```mermaid
stateDiagram-v2
    [*] --> pending
    pending --> validating : row arrives
    validating --> failed_validation : validation fails
    validating --> transforming : validation succeeds
    transforming --> failed_transformation : transform fails
    transforming --> processed : transform succeeds
    processed --> [*]
