<?php

return [
    // The credentials used to create the initial administrator user
    'admin' => [
        'email' => env('APP_ADMIN_EMAIL', null),
        'password' => env('APP_ADMIN_PASSWORD', null),
        'name' => env('APP_ADMIN_NAME', null),
    ],

    'environment' => [
        'slug' => env('ENVIRONMENT_SLUG', null),
        'name' => env('ENVIRONMENT_NAME', env('ENVIRONMENT_SLUG', null)),
    ],

    'guide' => [
        'url' => env('APP_URL_GUIDE', null),
    ],

    'instrument' => [
        'dedicatedType' => env('APP_DEDICATED_INSTRUMENT_TYPE')
    ],

    'userpool' => [
        'name' => env('AWS_COGNITO_USER_POOL', null),
        'url' => env('AWS_COGNITO_APP_URL_GUIDE', null),
    ],

    'version' => env('CORE_VERSION', '1.0.0'),
];
