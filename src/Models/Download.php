<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Download extends Model
{
    protected $table = 'downloads';

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
