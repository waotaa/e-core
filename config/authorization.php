<?php

// Organisation
$organisationGeneralPermissions = [
    // this permission allows seeing managers from the same organisation
    'manager.organisation.view',
];

// LocalParty
$localPartyGeneralPermissions = [
    ...$organisationGeneralPermissions,
    'localParty.viewAny',
    'localParty.view',
];

$localPartyAdministratorPermissions = [
    ...$localPartyGeneralPermissions,
    ...$organisationGeneralPermissions,
    'localParty.create',
    'localParty.update',
    'localParty.delete',
    'localParty.restore',
    'localParty.forceDelete',
];

// RegionalParty
$regionalPartyGeneralPermissions = [
    ...$organisationGeneralPermissions,
    'regionalParty.viewAny',
    'regionalParty.view',
];

$regionalPartyAdministratorPermissions = [
    ...$regionalPartyGeneralPermissions,
    ...$organisationGeneralPermissions,
    'regionalParty.create',
    'regionalParty.update',
    'regionalParty.delete',
    'regionalParty.restore',
    'regionalParty.forceDelete',
];

// NationalParty
$nationalPartyGeneralPermissions = [
    ...$organisationGeneralPermissions,
    'nationalParty.viewAny',
    'nationalParty.view',
];

$nationalPartyAdministratorPermissions = [
    ...$nationalPartyGeneralPermissions,
    ...$organisationGeneralPermissions,
    'nationalParty.create',
    'nationalParty.update',
    'nationalParty.delete',
    'nationalParty.restore',
    'nationalParty.forceDelete',
];

// Partnership
$partnershipGeneralPermissions = [
    ...$organisationGeneralPermissions,
    'partnership.viewAny',
    'partnership.view',
];

$partnershipAdministratorPermissions = [
    ...$partnershipGeneralPermissions,
    ...$organisationGeneralPermissions,
    'partnership.create',
    'partnership.update',
    'partnership.delete',
    'partnership.restore',
    'partnership.forceDelete',
];

// Region
$regionGeneralPermissions = [
    'region.viewAny',
    'region.view',
];

$regionAdministratorPermissions = [
    ...$regionGeneralPermissions,
];

// Township
$townshipGeneralPermissions = [
    'township.viewAny',
    'township.view',
];

$townshipAdministratorPermissions = [
    ...$townshipGeneralPermissions,
];

// Environment
$environmentGeneralPermissions = [
    'environment.viewAny',
    'environment.view',
];

// General
$generalPermissions = [
    ...$organisationGeneralPermissions,
    ...$regionalPartyGeneralPermissions,
    ...$nationalPartyGeneralPermissions,
    ...$partnershipGeneralPermissions,
    ...$regionGeneralPermissions,
    ...$townshipGeneralPermissions,
    ...$environmentGeneralPermissions,
];

// User
$userGeneralPermissions = [
    'role.viewAny',
    'role.view',

    'user.viewAny',
    'manager.viewAny',
];

$professionalPermissions = [
    // This permission allows you to see professionals
    'professional.viewAny',
    // This permission allows you to see a professional (detail page)
    'professional.view',
    // This permission allows you to create a professional
    'professional.create',
    // This permission allows you to delete a professional
    'professional.delete',
];

$organisationUserPermissions = [
    ...$organisationGeneralPermissions,
    ...$userGeneralPermissions,
    ...$professionalPermissions,

    // This permission allows you to create a manager for your organisation
    'manager.organisation.create',
    // This permission allows you to update a manager from your organisation
    'manager.organisation.update',
    // This permission allows you to delete a manager from your organisation
    'manager.organisation.delete',
    // This permission allows you to restore a manager from your organisation
    'manager.organisation.restore',
    // This permission allows you to forceDelete a manager from your organisation
    'manager.organisation.role.manage',
];

$userAdministratorPermissions = [
    ...$organisationUserPermissions,
    'user.view',
    'user.create',
    'user.update',
    'user.delete',
    'user.restore',
    'user.forceDelete',

    'manager.view',
    'manager.create',
    'manager.update',
    'manager.delete',
    'manager.restore',
    'manager.forceDelete',

    // This permission allows you to manage the role of a manager from your organisation
    'manager.role.manage',
    // This permission allows you to manage the organisation of a manager from your organisation
    'manager.organisation.manage',
];


// Environment
$environmentContentPermissions = [
    'newsItem.viewAny',
    'newsItem.view',
    'newsItem.create',
    'newsItem.update',
    'newsItem.delete',
    'newsItem.restore',
    'newsItem.forceDelete',
];
$environmentStylingPermissions = [
    'environment.style',
];

$environmentManagerPermissions = [
    'environment.update',
];

$environmentAdministratorPermissions = [
    ...$environmentContentPermissions,
    ...$environmentStylingPermissions,
    ...$environmentManagerPermissions,
    'environment.create',
    'environment.delete',
    'environment.restore',
    'environment.forceDelete'
];



// Instruments
$instrumentPropertyPermissions = [
    'clientCharacteristic.viewAny',
    'clientCharacteristic.view',

    'groupForm.viewAny',
    'groupForm.view',

    'implementation.viewAny',
    'implementation.view',

    'instrumentType.viewAny',
    'instrumentType.view',

    'location.viewAny',
    'location.view',

    'targetGroup.viewAny',
    'targetGroup.view',

    'neighbourhood.viewAny',
    'neighbourhood.view',
];
$instrumentPropertyManagementPermissions = [
    ...$instrumentPropertyPermissions,

    // All of these properties are ownerless at this time.
    'implementation.custom.create',
    'implementation.custom.update',
    'implementation.custom.delete',
    'implementation.custom.restore',
    'implementation.custom.forceDelete',

    'targetGroup.custom.create',
    'targetGroup.custom.update',
    'targetGroup.custom.delete',
    'targetGroup.custom.restore',
    'targetGroup.custom.forceDelete',

    'address.create',
    'address.update',
    'address.delete',
    'address.restore',
    'address.forceDelete',

    'contact.create',
    'contact.update',
    'contact.delete',
    'contact.restore',
    'contact.forceDelete',

    'location.create',
    'location.update',
    'location.delete',
    'location.restore',
    'location.forceDelete',

    'neighbourhood.create',
    'neighbourhood.update',
    'neighbourhood.delete',
    'neighbourhood.restore',
    'neighbourhood.forceDelete',
];

$instrumentRatingPermissions = [
    'rating.viewAny',
    'rating.view',
    'rating.update',
    'rating.delete',
    'rating.restore',
];

$instrumentAdminRatingPermissions = [
    'rating.create',
    'rating.forceDelete',
];


$organisationInstrumentPermissions = [
    ...$instrumentPropertyPermissions,
    ...$instrumentRatingPermissions,
    'instrument.viewAny',
    'instrument.organisation.view',
    'instrument.organisation.create',
    'instrument.organisation.update',
    'instrument.organisation.delete',
    'instrument.organisation.restore',
    'instrument.organisation.forceDelete',
];
$globalInstrumentPermissions = [
    ...$organisationInstrumentPermissions,
    ...$instrumentPropertyPermissions,
    ...$instrumentRatingPermissions,
    'instrument.viewAny',
    'instrument.view',
    'instrument.create',
    'instrument.update',
    'instrument.delete',
    'instrument.restore',
    'instrument.forceDelete',
];

// Providers
$organisationProviderPermissions = [
    'provider.viewAny',
    'provider.organisation.view',
    'provider.organisation.create',
    'provider.organisation.update',
    'provider.organisation.delete',
    'provider.organisation.restore',
    'provider.organisation.forceDelete',
];
$globalProviderPermissions = [
    ...$organisationProviderPermissions,
    'provider.viewAny',
    'provider.view',
    'provider.create',
    'provider.update',
    'provider.delete',
    'provider.restore',
    'provider.forceDelete'
];



return [
    // Models that need policy permissions (viewAny, view, create, update, delete, restore, forceDelete)
    'model-permissions' => [
        'clientCharacteristic',
        'environment',
        'groupForm',
        'implementation',
        'instrument',
        'instrument.organisation',
        'instrumentType',
        'location',
        'manager',
        'newsItem',
        'partnership',
        'provider',
        'provider.organisation',
        'rating',
        'region',
        'targetGroup',
        'township',
        'user',
    ],
    // Roles and their associated permissions
    'matrix' => [
        'administrator' => [
            ...$generalPermissions,
            ...$regionalPartyAdministratorPermissions,
            ...$nationalPartyAdministratorPermissions,
            ...$environmentAdministratorPermissions,
            ...$partnershipAdministratorPermissions,

            ...$globalInstrumentPermissions,
            ...$globalProviderPermissions,
            ...$instrumentAdminRatingPermissions,

            ...$userAdministratorPermissions,
        ],
        'instrument-manager' => [
            ...$generalPermissions,
            ...$globalInstrumentPermissions,
            ...$globalProviderPermissions,
        ],
        'instrument-manager-organisation' => [
            ...$generalPermissions,
            ...$organisationInstrumentPermissions,
            ...$organisationProviderPermissions,
        ],
        'environment-manager' => [
            ...$generalPermissions,
            ...$instrumentPropertyManagementPermissions,
            ...$environmentManagerPermissions,

            ...$environmentContentPermissions,
            ...$environmentStylingPermissions,
            ...$organisationUserPermissions,

            ...$organisationInstrumentPermissions,
            ...$organisationProviderPermissions,
        ],
        'environment-content-manager' => [
            ...$generalPermissions,
            ...$environmentContentPermissions,
        ],
        'environment-theme-manager' => [
            ...$generalPermissions,
            ...$environmentStylingPermissions,
        ],
        'user-manager-organisation' => [
            ...$generalPermissions,
            ...$organisationUserPermissions,
        ],
    ],

    'super-admin-role' => 'super-admin',

    'roles' => [
        'super-admin' => 'Super Admin',
        'administrator' => 'Administrator',
        'instrument-manager' => 'Instrument beheerder',
        'instrument-manager-organisation' => 'Instrument beheerder voor organisatie',
        'environment-manager' => 'Omgeving beheerder',
        'environment-content-manager' => 'Omgeving content beheerder',
        'environment-theme-manager' => 'Omgeving opmaak beheerder',
        'user-manager-organisation' => 'Gebruikers beheerder voor organisatie'
    ],

    'assignable-roles' => [
        'administrator' => [
            'administrator',
            'instrument-manager',
            'environment-manager-national',
            'instrument-manager-organisation',
            'environment-content-manager',
            'environment-theme-manager',
            'user-manager-organisation'
        ],
        'instrument-manager' => [],
        'instrument-manager-organisation' => [],
        'environment-manager' => [
            'instrument-manager-organisation',
            'environment-manager',
            'environment-content-manager',
            'environment-theme-manager',
        ],
        'environment-content-manager' => [],
        'environment-theme-manager' => [],
        'user-manager-organisation' => [
            'instrument-manager-organisation',
            'user-manager-organisation',
        ],
    ]
];
