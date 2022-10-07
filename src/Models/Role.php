<?php

namespace Vng\EvaCore\Models;

class Role extends \Spatie\Permission\Models\Role
{
    const SUPER_ADMIN_ROLE = 'super-admin';
    const ROLES = [
        'super-admin' => 'Super Admin',
        'administrator' => 'Administrator',

        'instrument-manager' => 'Instrument beheerder',
        'instrument-manager-organisation' => 'Instrument beheerder organisatie',

        'environment-manager' => 'Omgeving beheerder',
        'environment-content-manager' => 'Omgeving content beheerder',
        'environment-theme-manager' => 'Omgeving opmaak beheerder',
        'environment-user-manager' => 'Omgeving gebruikers beheerder',

        'national-user-manager' => 'Nationale gebruikers beheerder',
        'user-manager-organisation' => 'Organisatie gebruikers beheerder'
    ];

    const ASSIGNABLE_ROLES = [
        'administrator' => [
            'administrator',
            'instrument-manager',
            'environment-manager-national',
            'instrument-manager-organisation',
            'environment-content-manager',
            'environment-theme-manager',
            'environment-user-manager',
            'national-user-manager',
            'user-manager-organisation'
        ],
        'instrument-manager' => [],
        'instrument-manager-national' => [],
        'instrument-manager-organisation' => [],
        'environment-manager' => [
            'instrument-manager-organisation',
            'environment-manager',
            'environment-content-manager',
            'environment-theme-manager',
            'environment-user-manager'
        ],
        'environment-content-manager' => [],
        'environment-theme-manager' => [],
        'environment-user-manager' => [
            'instrument-manager-organisation',
            'environment-user-manager',
        ],
        'national-user-manager' => [
            'instrument-manager-national',
            'national-user-manager',
        ],
        'user-manager-organisation' => [
            'instrument-manager-organisation',
            'user-manager-organisation',
        ],
    ];

    public function getNameTranslatedAttribute(): string
    {
        return $this::ROLES[$this->name];
    }
}
