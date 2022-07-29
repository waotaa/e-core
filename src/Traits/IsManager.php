<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Models\Manager;

trait IsManager
{
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }
}
