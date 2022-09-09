<?php

namespace Vng\EvaCore\Interfaces;

use Vng\EvaCore\Models\Instrument;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface IsOwnerInterface
{
    public function getOwnerId();
    public function getOwnerClass(): string;
    public function getOwnerType(): string;
}
