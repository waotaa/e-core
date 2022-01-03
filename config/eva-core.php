<?php

return [
    'environment' => [
        'slug' => env('ENVIRONMENT_SLUG', null),
        'name' => env('ENVIRONMENT_NAME', env('ENVIRONMENT_SLUG', null)),
    ],
];
