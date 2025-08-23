<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'entry_fee_amount' => 50000_00, // in cents
    'currency' => 'eur',
    'success_url' => env('APP_URL').'/franchise/apply/success?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => env('APP_URL').'/franchise/apply/canceled',
];
