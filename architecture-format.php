<?php

// Api response formate

return response()->json([
    'status' => 'error',
    'error' => [
        'code' => 'ERROR_CODE',
        'message' => 'Error message'
    ]
], HTTP_CODE);

// Example

if (!$shop) {
    return response()->json([
        'status' => 'error',
        'error' => [
            'code' => 'MISSING_SHOP_PARAMETER',
            'message' => 'The shop parameter is required.'
        ]
    ], 400);
}


// error codes

| Situation     | Code                |
| ------------- | ------------------- |
| Missing param | `MISSING_PARAMETER` |
| Auth issue    | `UNAUTHORIZED`      |
| Validation    | `VALIDATION_ERROR`  |
| Server fail   | `SERVER_ERROR`      |
| Missing shop  | `MISSING_SHOP`                  |