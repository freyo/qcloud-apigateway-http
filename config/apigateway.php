<?php

return [

    'response_type' => env('APIGATEWAY_RESPONSE_TYPE', 'collection'),
    'secret_id' => env('APIGATEWAY_SECRET_ID', ''),
    'secret_key' => env('APIGATEWAY_SECRET_KEY', ''),
    'log' => [
        'file' => storage_path('logs/apigateway.log'),
        'level' => 'debug',
    ],

];
