<?php

return [
    'prefix' => env('ELASTIC_INDEX_PREFIX'),
    'instances' => [
        'default' => [
            'cloud_id' => env('ELASTIC_CLOUD_ID'),
            'username' => env('ELASTIC_USERNAME'),
            'password' => env('ELASTIC_PASSWORD'),
        ],
        'public' => [
            'cloud_id' => env('ELASTIC_PUBLIC_CLOUD_ID'),
            'username' => env('ELASTIC_PUBLIC_USERNAME'),
            'password' => env('ELASTIC_PUBLIC_PASSWORD'),
        ]
    ]
];
