<?php

namespace Vng\EvaCore\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface OrganisationEntityInterface
{
    public function organisation(): BelongsTo;
}
