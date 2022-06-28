<?php

namespace Vng\EvaCore\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface IsInstrumentWatcherInterface
{
    public function instrumentTrackers(): HasMany;
}
