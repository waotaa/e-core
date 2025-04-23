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

// General
$generalPermissions = [
    ...$organisationGeneralPermissions,
    ...$localPartyGeneralPermissions,
    ...$regionalPartyGeneralPermissions,
    ...$nationalPartyGeneralPermissions,
    ...$partnershipGeneralPermissions,
    ...$regionGeneralPermissions,
    ...$townshipGeneralPermissions,

    'release.viewAny',
    'release.view',
];

$administratorPermissions = [
    'release.create',
    'release.update',
    'release.delete',
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
    'manager.create.within-organisation',
    // This permission allows you to update a manager from your organisation
    'manager.update.within-organisation',
    // This permission allows you to delete a manager from your organisation
    'manager.delete.within-organisation',
    // This permission allows you to restore a manager from your organisation
    'manager.restore.within-organisation',
    // This permission allows you to forceDelete a manager from your organisation
    'manager.forceDelete.within-organisation',

    // This permission allows you to manage the role of a manager from your organisation
    'manager.assign-role.within-organisation',

    // for attaching and detaching the organisation - manager relation
    'manager.assign-organisation.within-organisation',
    'organisation.assign-manager.within-organisation',
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

    // For attaching and detaching the manager - role relation
    'manager.assign-role',
    'role.assign-manager',

    // For attaching and detaching the manager - organisation relation
    'manager.assign-organisation',
    'organisation.assign-manager',
];


// Environment
$environmentGeneralPermissions = [
    'environment.viewAny',
];

$newsItemPermissions = [
    'newsItem.viewAny',
    'newsItem.view',
    'newsItem.create',
    'newsItem.update',
    'newsItem.delete',
];

$organisationEnvironmentPermissions = [
    ...$newsItemPermissions,
    'environment.viewAny',
    'environment.organisation.view',
//    'environment.organisation.create',
    'environment.organisation.update',
//    'environment.organisation.delete',
//    'environment.organisation.restore',
//    'environment.organisation.forceDelete',
];

$newsItemGlobalPermissions = [
    'newsItem.viewAll',
];

$globalEnvironmentPermissions = [
    ...$organisationEnvironmentPermissions,
    ...$newsItemGlobalPermissions,
    'environment.view',
    'environment.viewAll',
    'environment.create',
    'environment.update',
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

// Download
$organisationDownloadPermissions = [
    'download.viewAny',
    'download.organisation.view',
    'download.organisation.create',
    'download.organisation.update',
    'download.organisation.delete',
    'download.organisation.restore',
    'download.organisation.forceDelete',
];

$globalDownloadPermissions = [
    ...$organisationDownloadPermissions,
    'download.viewAny',
    'download.viewAll',
    'download.view',
    'download.create',
    'download.update',
    'download.delete',
    'download.restore',
    'download.forceDelete'
];

// TargetGroup
$organisationTargetGroupPermissions = [
    'targetGroup.viewAny',
    'targetGroup.organisation.view',
    'targetGroup.organisation.create',
    'targetGroup.organisation.update',
    'targetGroup.organisation.delete',
    'targetGroup.organisation.restore',
    'targetGroup.organisation.forceDelete',
];

$globalTargetGroupPermissions = [
    ...$organisationTargetGroupPermissions,
    'targetGroup.viewAny',
    'targetGroup.viewAll',
    'targetGroup.view',
    'targetGroup.create',
    'targetGroup.update',
    'targetGroup.delete',
    'targetGroup.restore',
    'targetGroup.forceDelete'
];

// Instruments
$instrumentPropertyPermissions = [
    'clientCharacteristic.viewAny',
    'groupForm.viewAny',
    'implementation.viewAny',
    'targetGroup.viewAny',
    'tile.viewAny',

    'neighbourhood.viewAny',
    'neighbourhood.view',
];
$instrumentPropertyManagementPermissions = [
    ...$instrumentPropertyPermissions,

    'clientCharacteristic.view',
    'groupForm.view',
    'implementation.view',
    'targetGroup.view',
    'tile.view',

    // All of these properties are ownerless at this time.
    'implementation.custom.create',
    'implementation.custom.update',
    'implementation.custom.delete',
    'implementation.custom.restore',
    'implementation.custom.forceDelete',

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

// Exports
$organisationExportPermissions = [
    'export.viewAny',
    'export.organisation.view',
    'export.organisation.create',
    'export.organisation.delete',
];
$globalExportPermissions = [
    ...$organisationExportPermissions,
    'export.viewAny',
    'export.viewAll',
    'export.view',
    'export.create',
    'export.delete',
];

$faqEditPermissions = [
    'faq.create',
    'faq.update',
    'faq.delete'
];

return [
    // Models that need policy permissions (viewAny, view, create, update, delete, restore, forceDelete)
    'model-permissions' => [
        'address',
        'clientCharacteristic',
        'contact',
        'download',
        'environment.organisation',
        'environment',
        'export',
        'groupForm',
        'implementation',
        'instrument',
        'instrument.organisation',
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
        'release',
        'targetGroup',
        'tile',
        'township',
        'user',
        'video',
    ],
    // Roles and their associated permissions
    'matrix' => [
        'administrator' => [
            ...$administratorPermissions,
            ...$generalPermissions,
            ...$environmentGeneralPermissions,
            ...$instrumentPropertyManagementPermissions,

            ...$localPartyAdministratorPermissions,
            ...$regionalPartyAdministratorPermissions,
            ...$nationalPartyAdministratorPermissions,
            ...$globalEnvironmentPermissions,
            ...$partnershipAdministratorPermissions,

            ...$globalAddressPermissions,
            ...$globalContactPermissions,
            ...$globalDownloadPermissions,
            ...$globalInstrumentPermissions,
            ...$globalProviderPermissions,
            ...$globalExportPermissions,
            ...$instrumentAdminRatingPermissions,

            ...$userAdministratorPermissions,
            ...$faqEditPermissions
        ],
        'observer' => [
            // anyone can see..
            'manager.organisation.view',
            'localParty.viewAny',
            'localParty.view',
            'regionalParty.viewAny',
            'regionalParty.view',
            'nationalParty.viewAny',
            'nationalParty.view',
            'partnership.viewAny',
            'partnership.view',
            'region.viewAny',
            'region.view',
            'township.viewAny',
            'township.view',
            'release.viewAny',
            'release.view',
            'role.viewAny',
            'role.view',
            'user.viewAny',
            'manager.viewAny',
            'professional.viewAny',
            'professional.view',
            'environment.viewAny',
            'newsItem.viewAny',
            'address.viewAny',
            'contact.viewAny',
            'download.viewAny',
            'targetGroup.viewAny',
            'clientCharacteristic.viewAny',
            'clientCharacteristic.view',
            'groupForm.viewAny',
            'groupForm.view',
            'implementation.viewAny',
            'implementation.view',
            'targetGroup.viewAny',
            'targetGroup.view',
            'tile.viewAny',
            'tile.view',
            'neighbourhood.viewAny',
            'neighbourhood.view',
            'rating.viewAny',
            'rating.view',
            'instrument.viewAny',
            'provider.viewAny',
            'export.viewAny',

            // next level view rights
            'user.view',
            'manager.view',
            'newsItem.view',
            'newsItem.viewAll',
            'environment.view',
            'environment.viewAll',
            'address.viewAll',
            'address.view',
            'contact.viewAll',
            'contact.view',
            'download.viewAll',
            'download.view',
            'targetGroup.viewAll',
            'targetGroup.view',
            'instrument.viewAll',
            'instrument.view',
            'provider.viewAll',
            'provider.view',
            'export.viewAll',
            'export.view'
        ],
        'instrument-manager' => [
            ...$generalPermissions,
            ...$globalAddressPermissions,
            ...$globalContactPermissions,
            ...$globalDownloadPermissions,
            ...$globalInstrumentPermissions,
            ...$globalProviderPermissions,
            ...$globalExportPermissions,
        ],
        'instrument-manager-organisation' => [
            ...$generalPermissions,
            ...$organisationAddressPermissions,
            ...$organisationContactPermissions,
            ...$organisationDownloadPermissions,
            ...$organisationTargetGroupPermissions,
            ...$organisationInstrumentPermissions,
            ...$organisationProviderPermissions,
            ...$organisationExportPermissions
        ],
        'environment-manager' => [
            ...$generalPermissions,
            ...$environmentGeneralPermissions,
//            ...$instrumentPropertyManagementPermissions,

            ...$organisationEnvironmentPermissions,

            ...$organisationUserPermissions,
            ...$organisationAddressPermissions,
            ...$organisationContactPermissions,
            ...$organisationDownloadPermissions,
            ...$organisationTargetGroupPermissions,
            ...$organisationInstrumentPermissions,
            ...$organisationProviderPermissions,
            ...$organisationExportPermissions
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
        'observer' => 'Observer',
        'instrument-manager' => 'Instrument beheerder',
        'environment-manager' => 'Omgeving beheerder',
        'instrument-manager-organisation' => 'Instrument beheerder voor organisatie',
        'user-manager-organisation' => 'Gebruikers beheerder voor organisatie'
    ],

    'assignable-roles' => [
        'administrator' => [
            'administrator',
            'observer',
            'instrument-manager',
            'instrument-manager-organisation',
            'environment-manager',
            'user-manager-organisation'
        ],
        'instrument-manager' => [],
        'environment-manager' => [
            'environment-manager',
            'user-manager-organisation',
            'instrument-manager-organisation',
        ],
        'user-manager-organisation' => [
            'instrument-manager-organisation',
            'user-manager-organisation',
        ],
        'instrument-manager-organisation' => [],
    ]
];
