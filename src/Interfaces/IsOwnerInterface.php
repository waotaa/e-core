<?php

namespace Vng\EvaCore\Interfaces;

use Vng\EvaCore\Models\Instrument;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface IsOwnerInterface
{
    public function ownedInstruments(): MorphMany;

    public function ownsInstrument(Instrument $instrument): bool;

    public function ownedProviders(): MorphMany;

    public function getShortClassname(): string;
}
