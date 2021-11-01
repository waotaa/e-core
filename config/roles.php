<?php

return [
    'super-admin-role' => 'super-admin',

    'roles' => [
        'super-admin' => 'Super Admin',
        'administrator' => 'Administrator',
        'instrument-manager' => 'Instrument beheerder',
        'instrument-manager-national' => 'Instrument beheerder nationaal',
        'instrument-manager-association' => 'Instrument beheerder organisatie',
        'environment-manager' => 'Omgeving beheerder',
        'environment-content-manager' => 'Omgeving content beheerder',
        'environment-theme-manager' => 'Omgeving opmaak beheerder',
        'environment-user-manager' => 'Omgeving gebruikers beheerder',
        'national-user-manager' => 'Nationale gebruikers beheerder',
        'association-user-manager' => 'Organisatie gebruikers beheerder'
    ],

    'assignable-roles' => [
        'administrator' => [
            'administrator',
            'instrument-manager',
            'environment-manager-national',
            'instrument-manager-association',
            'environment-content-manager',
            'environment-theme-manager',
            'environment-user-manager',
            'national-user-manager',
            'association-user-manager'
        ],
        'instrument-manager' => [],
        'instrument-manager-national' => [],
        'instrument-manager-association' => [],
        'environment-manager' => [
            'instrument-manager-association',
            'environment-manager',
            'environment-content-manager',
            'environment-theme-manager',
            'environment-user-manager'
        ],
        'environment-content-manager' => [],
        'environment-theme-manager' => [],
        'environment-user-manager' => [
            'instrument-manager-association',
            'environment-user-manager',
        ],
        'national-user-manager' => [
            'instrument-manager-national',
            'national-user-manager',
        ],
        'association-user-manager' => [
            'instrument-manager-association',
            'association-user-manager',
        ],
    ]
];
