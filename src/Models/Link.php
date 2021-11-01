<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Link extends Model
{
    protected $table = 'links';

    protected $fillable = [
        'label',
        'url'
    ];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
