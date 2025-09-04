<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Settings Groups
    |--------------------------------------------------------------------------
    |
    | Here you can configure the available settings groups in the admin panel.
    |
    */

    'groups' => [
        'general' => 'General',
        'payment' => 'Payment',
        'email' => 'Email',
        'social' => 'Social',
        'firebase' => 'Firebase',
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for payment gateway settings.
    |
    */

    'payment_gateway' => [
        'key_id' => env('PAYMENT_GATEWAY_KEY_ID'),
        'key_secret' => env('PAYMENT_GATEWAY_KEY_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for Firebase settings.
    |
    */

    'firebase' => [
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'api_key' => env('FIREBASE_API_KEY'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
        'app_id' => env('FIREBASE_APP_ID'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
    ],
];