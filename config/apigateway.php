<?php

return [

    'response_type' => env('APIGATEWAY_RESPONSE_TYPE', 'collection'),
    'secret_id' => env('APIGATEWAY_SECRET_ID', ''),
    'secret_key' => env('APIGATEWAY_SECRET_KEY', ''),
    'region' => env('APIGATEWAY_REGION', ''),
    'log' => [
        'file' => env('APIGATEWAY_LOG_FILE', storage_path('logs/apigateway.log')),
        'level' => env('APIGATEWAY_LOG_LEVEL', 'debug'),
    ],
    'source' => env('APIGATEWAY_SOURCE', ''),
    'fingerprint' => env('APIGATEWAY_FINGERPRINT', false),

];
