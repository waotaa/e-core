<?php

namespace Vng\EvaCore\Interfaces;

use Vng\EvaCore\Models\Instrument;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface IsOwnerInterface
{
    public function getOwnerClass(): string;

    public function getOwnerId();

    public function getOwnerType(): string;

    public function ownedInstruments(): MorphMany;

    public function ownsInstrument(Instrument $instrument): bool;

    public function ownedProviders(): MorphMany;
}
