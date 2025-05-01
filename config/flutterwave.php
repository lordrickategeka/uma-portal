<?php
// config/flutterwave.php

return [
    /*
    |--------------------------------------------------------------------------
    | Flutterwave Secret Key
    |--------------------------------------------------------------------------
    |
    | This is the secret key provided by Flutterwave for API interactions
    |
    */
    'secret_key' => env('FLW_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Flutterwave Public Key
    |--------------------------------------------------------------------------
    |
    | This is the public key provided by Flutterwave for API interactions
    |
    */
    'public_key' => env('FLW_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Flutterwave API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for Flutterwave API calls
    |
    */
    'base_url' => env('FLW_BASE_URL', 'https://api.flutterwave.com/v3'),

    /*
    |--------------------------------------------------------------------------
    | Flutterwave Encryption Key
    |--------------------------------------------------------------------------
    |
    | This is the encryption key provided by Flutterwave
    |
    */
    'encryption_key' => env('FLW_ENCRYPTION_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Flutterwave Logo URL
    |--------------------------------------------------------------------------
    |
    | The URL of your application logo to be shown on payment pages
    |
    */
    'logo_url' => env('FLUTTERWAVE_LOGO_URL'),

    /*
    |--------------------------------------------------------------------------
    | Test Callback URL
    |--------------------------------------------------------------------------
    |
    | The callback URL to use for testing environments
    | This is especially useful for local development with tools like ngrok
    |
    */
    'test_callback_url' => env('FLUTTERWAVE_TEST_CALLBACK_URL', 'https://91b6-41-210-145-1.ngrok-free.app/payments/callback'),
];