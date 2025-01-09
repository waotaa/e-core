<?php

return [
    'prefix' => env('ELASTIC_INDEX_PREFIX'),
    'field_limit' => env('ELASTIC_FIELD_LIMIT', 1000),
    'kibana' => [
        'host' => env('KIBANA_API_HOST'),
        'apiKey' => env('KIBANA_API_KEY')
    ],
    'instances' => [
        'default' => [
            'cloud_id' => env('ELASTIC_CLOUD_ID'),
            'username' => env('ELASTIC_USERNAME'),
            'password' => env('ELASTIC_PASSWORD'),
        ],
        'behaviour' => [
            'cloud_id' => env('ELASTIC_BEHAVIOUR_CLOUD_ID', env('ELASTIC_CLOUD_ID')),
            'username' => env('ELASTIC_BEHAVIOUR_USERNAME', env('ELASTIC_USERNAME')),
            'password' => env('ELASTIC_BEHAVIOUR_PASSWORD', env('ELASTIC_PASSWORD')),
            'prefix' => env('ELASTIC_BEHAVIOUR_PASSWORD', env('ELASTIC_INDEX_PREFIX')),
        ],
        'public' => [
            'cloud_id' => env('ELASTIC_PUBLIC_CLOUD_ID'),
            'username' => env('ELASTIC_PUBLIC_USERNAME'),
            'password' => env('ELASTIC_PUBLIC_PASSWORD'),
        ]
    ]
];
