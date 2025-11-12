<?php

return [
    'rate' => env('COMMISSION_RATE', 10),
    'payout_threshold' => env('PAYOUT_THRESHOLD', 100),
    'currency' => env('PAYOUT_CURRENCY', 'usd'),
];
