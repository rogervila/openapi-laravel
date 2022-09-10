<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAPI Log Settings
    |--------------------------------------------------------------------------
    |
    | You may configure a specific log
    | channel for OpenAPI validation messages
    | and define additional context data.
    |
    */

    'log' => [
        'channel' => env('LOG_CHANNEL', 'null'),
        'context' => [
            'openapi' => true,
        ],
    ],
];
