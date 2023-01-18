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
    ...$localPartyGeneralPermissions,
    ...$regionalPartyGeneralPermissions,
    ...$nationalPartyGeneralPermissions,
    ...$partnershipGeneralPermissions,
    ...$regionGeneralPermissions,
    ...$townshipGeneralPermissions,
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


// Address
$organisationAddressPermissions = [
    'address.viewAny',
    'address.organisation.view',
    'address.organisation.create',
    'address.organisation.update',
    'address.organisation.delete',
    'address.organisation.restore',
    'address.organisation.forceDelete',
];

$globalAddressPermissions = [
    ...$organisationAddressPermissions,
    'address.viewAny',
    'address.viewAll',
    'address.view',
    'address.create',
    'address.update',
    'address.delete',
    'address.restore',
    'address.forceDelete'
];

// Contact
$organisationContactPermissions = [
    'contact.viewAny',
    'contact.organisation.view',
    'contact.organisation.create',
    'contact.organisation.update',
    'contact.organisation.delete',
    'contact.organisation.restore',
    'contact.organisation.forceDelete',
];

$globalContactPermissions = [
    ...$organisationContactPermissions,
    'contact.viewAny',
    'contact.viewAll',
    'contact.view',
    'contact.create',
    'contact.update',
    'contact.delete',
    'contact.restore',
    'contact.forceDelete'
];

// Instruments
$instrumentPropertyPermissions = [
    // moet weg. AddressPolicyTest test verwachtingen ook aanpassen
    'address.viewAny',
    'address.view',
    // moet weg. ContactPolicyTest test verwachtingen ook aanpassen`z
    'contact.viewAny',
    'contact.view',

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

    'tile.viewAny',
    'tile.view',

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
    'instrument.viewAll',
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
    'provider.viewAll',
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
        'address',
        'clientCharacteristic',
        'contact',
        'download',
        'environment',
        'groupForm',
        'implementation',
        'instrument',
        'instrument.organisation',
        'instrumentType',
        'link',
        'localParty',
        'location',
        'manager',
        'nationalParty',
        'newsItem',
        'organisation',
        'partnership',
        'professional',
        'provider',
        'provider.organisation',
        'rating',
        'region',
        'regionalParty',
        'targetGroup',
        'tile',
        'township',
        'user',
        'video',
    ],
    // Roles and their associated permissions
    'matrix' => [
        'administrator' => [
            ...$generalPermissions,
            ...$environmentGeneralPermissions,

            ...$localPartyAdministratorPermissions,
            ...$regionalPartyAdministratorPermissions,
            ...$nationalPartyAdministratorPermissions,
            ...$environmentAdministratorPermissions,
            ...$partnershipAdministratorPermissions,

            ...$globalAddressPermissions,
            ...$globalContactPermissions,
            ...$globalInstrumentPermissions,
            ...$globalProviderPermissions,
            ...$instrumentAdminRatingPermissions,

            ...$userAdministratorPermissions,
        ],
        'instrument-manager' => [
            ...$generalPermissions,
            ...$globalAddressPermissions,
            ...$globalContactPermissions,
            ...$globalInstrumentPermissions,
            ...$globalProviderPermissions,
        ],
        'instrument-manager-organisation' => [
            ...$generalPermissions,
            ...$organisationAddressPermissions,
            ...$organisationContactPermissions,
            ...$organisationInstrumentPermissions,
            ...$organisationProviderPermissions,
        ],
        'environment-manager' => [
            ...$generalPermissions,
            ...$environmentGeneralPermissions,
            ...$instrumentPropertyManagementPermissions,

            ...$environmentManagerPermissions,
            ...$environmentContentPermissions,
            ...$environmentStylingPermissions,

            ...$organisationUserPermissions,
            ...$organisationAddressPermissions,
            ...$organisationContactPermissions,
            ...$organisationInstrumentPermissions,
            ...$organisationProviderPermissions,
        ],
        'environment-content-manager' => [
            ...$generalPermissions,
            ...$environmentGeneralPermissions,
            ...$environmentContentPermissions,
        ],
        'environment-theme-manager' => [
            ...$generalPermissions,
            ...$environmentGeneralPermissions,
            ...$environmentStylingPermissions,
        ],
        'user-manager-organisation' => [
            ...$generalPermissions,
            ...$environmentGeneralPermissions,
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
