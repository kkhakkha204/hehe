<?php

return [
    'webhook_token' => env('SEPAY_WEBHOOK_TOKEN'),
    'pattern' => env('SEPAY_MATCH_PATTERN', 'SE'),

    // Bank info
    'bank_id' => env('SEPAY_BANK_ID', 'MB'),
    'bank_name' => env('SEPAY_BANK_NAME', 'MB Bank'),
    'account_number' => env('SEPAY_ACCOUNT_NUMBER'),
    'account_name' => env('SEPAY_ACCOUNT_NAME'),
];
