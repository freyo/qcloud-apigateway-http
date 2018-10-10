<?php

return [

    'response_type' => env('APIGATEWAY_RESPONSE_TYPE', 'collection'),
    'secret_id' => env('APIGATEWAY_SECRET_ID', 'your-secret-id'),
    'secret_key' => env('APIGATEWAY_SECRET_KEY', 'your-secret-key'),
    'log' => [
        'file' => storage_path('logs/apigateway.log'),
        'level' => 'debug',
    ],

];