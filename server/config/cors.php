<?php

return [
    'paths' => [
        'api/*',
        'upload/*',
        'login',
        'logout',
        'sanctum/csrf-cookie',
        'storage/*'
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];