<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Observers\LinkObserver;

class Link extends Model
{
    protected $table = 'links';

    protected $fillable = [
        'label',
        'url'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(LinkObserver::class);
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
