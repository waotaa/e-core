<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Models\Instrument;

trait IsMember
{
    abstract public function associateables(): HasMany;

    abstract public function nationalParties(): MorphToMany;

    abstract public function regions(): MorphToMany;

    abstract public function partnerships(): MorphToMany;

    abstract public function townships(): MorphToMany;

    public function getAssociations(): ?Collection
    {
        $associateables = $this->associateables()->get();
        if (is_null($associateables)) {
            return null;
        }
        return $associateables->map(fn ($associateable) => $associateable->association);
    }

    public function hasAnyAssociation(): bool
    {
        return !is_null($this->associateables()->get()) && $this->associateables()->count();
    }

    public function usersShareAssociation(EvaUserInterface $user): bool
    {
        if (!$this->hasAnyAssociation()) {
            return false;
        }
        $sharedAssociation = $this->getAssociations()
            ->filter(fn ($association) => $association->hasMember($user));
        return $sharedAssociation->count() > 0;
    }

    public function managesInstrument(Instrument $instrument): bool
    {
        if ($this->hasAnyAssociation() &&
            $this->getAssociations()->contains(function ($association) use ($instrument) {
                return $association->ownsInstrument($instrument);
            })
        ) {
            return true;
        }
        return false;
    }

    public function getAssociationsOwnedInstruments()
    {
        return $this->getAssociations()
            ->flatMap(fn ($association) => $association->ownedInstruments)
            ->unique('id');
    }
}
