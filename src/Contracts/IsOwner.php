<?php

namespace Vng\EvaCore\Contracts;

use Vng\EvaCore\Models\Instrument;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface IsOwner
{
    public function ownedInstruments(): MorphMany;

    public function ownsInstrument(Instrument $instrument): bool;

    public function ownedProviders(): MorphMany;

    public function getShortClassname(): string;
}
