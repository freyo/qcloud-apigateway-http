<?php

return [

    'response_type' => env('APIGATEWAY_RESPONSE_TYPE', 'collection'),
    'secret_id'     => env('APIGATEWAY_SECRET_ID', ''),
    'secret_key'    => env('APIGATEWAY_SECRET_KEY', ''),
    'region'        => env('APIGATEWAY_REGION', ''),
    'log'           => [
        'file'  => env('APIGATEWAY_LOG_FILE', storage_path('logs/apigateway.log')),
        'level' => env('APIGATEWAY_LOG_LEVEL', 'debug'),
    ],
    'source'        => env('APIGATEWAY_SOURCE', ''),
    'fingerprint'   => env('APIGATEWAY_FINGERPRINT', false),

    // Guzzle Request Options
    // See: http://docs.guzzlephp.org/en/stable/request-options.html
    'http'          => [
        'http_errors'     => env('APIGATEWAY_HTTP_ERRORS', false),
        'expect'          => env('APIGATEWAY_HTTP_EXPECT', false),
        'decode_content'  => env('APIGATEWAY_HTTP_DECODE_CONTENT', true),
        'connect_timeout' => env('APIGATEWAY_HTTP_CONNECT_TIMEOUT', 0),
        'timeout'         => env('APIGATEWAY_HTTP_TIMEOUT', 5),
    ],

];
