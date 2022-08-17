<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Manager extends Model
{
    use HasFactory, MutationLog;

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
        $sharedOrganisations = $this->organisations->filter(fn (Organisation $organisation) => $organisation->hasMember($manager));
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
}
