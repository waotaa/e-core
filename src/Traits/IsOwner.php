<?php

namespace Vng\EvaCore\Traits;

use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Provider;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ReflectionClass;

trait IsOwner
{
    public function ownedInstruments(): MorphMany
    {
        return $this->morphMany(Instrument::class, 'owner');
    }

    public function ownsInstrument(Instrument $instrument): bool
    {
        return $this->ownedInstruments->contains($instrument->id);
    }

    public function ownedProviders(): MorphMany
    {
        return $this->morphMany(Provider::class, 'owner');
    }

    public function ownedItemsCount(): int
    {
        $instrumentCount = $this->ownedInstruments()->count();
        $providerCount = $this->ownedProviders()->count();
        return $instrumentCount + $providerCount;
    }

    public function getShortClassname(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }
}
