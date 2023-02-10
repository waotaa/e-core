<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\Interfaces\IsInstrumentWatcherInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Vng\EvaCore\Traits\IsInstrumentWatcher;

class Manager extends Model implements IsInstrumentWatcherInterface
{
    use HasRoles, HasFactory, MutationLog, IsInstrumentWatcher;

    protected $guard_name = 'web';

    protected $attributes = [
        'months_unupdated_limit' => 6
    ];

    protected $fillable = [
        'givenName',
        'surName',
        'email',
        'months_unupdated_limit',
    ];

    public function getFirstNameAttribute()
    {
        return $this->givenName;
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['givenName'] = $value;
    }

    public function getLastNameAttribute()
    {
        return $this->surName;
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['surName'] = $value;
    }

    public function getNameAttribute()
    {
        return $this->givenName . ' ' . $this->surName;
    }

    public function getFullNameAttribute()
    {
        return $this->givenName . ' ' . $this->surName;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'created_by_manager_id');
    }

    public function isCreatedBy(Manager $manager): bool
    {
        return $this->createdBy && $this->createdBy->id === $manager->id;
    }

    public function organisations(): BelongsToMany
    {
        return $this->belongsToMany(Organisation::class);
    }

    public function hasAnyOrganisations(): bool
    {
        return !is_null($this->organisations()->get()) && $this->organisations()->count();
    }

    public function hasOrganisation(Organisation $organisation): bool
    {
        return $this->hasAnyOrganisations() && $this->organisations()->contains($organisation);
    }

    public function managersShareOrganisation(Manager $manager): bool
    {
        if (!$this->hasAnyOrganisations()) {
            return false;
        }
        $sharedOrganisations = $this->organisations
            ->filter(fn (Organisation $organisation) => $organisation->hasMember($manager));
        return $sharedOrganisations->count() > 0;
    }

    public function managesInstrument(Instrument $instrument): bool
    {
        if ($this->hasAnyOrganisations() &&
            $this->organisations->contains(function (Organisation $organisation) use ($instrument) {
                return $organisation->ownsInstrument($instrument);
            })
        ) {
            return true;
        }
        return false;
    }

    public function getOrganisationsOwnedInstruments()
    {
        return $this->organisations
            ->flatMap(fn (Organisation $organisation) => $organisation->ownedInstruments)
            ->unique('id');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole(config('authorization.super-admin-role'));
    }

    public function getAssignableRoles(): array
    {
        $assignableRoles = [];
        /** @var Role $role */
        foreach ($this->roles as $role) {
            $assignableRoles = array_unique(array_merge($assignableRoles, $role->getAssignableRoles()));
        }
        return $assignableRoles;
    }
}
