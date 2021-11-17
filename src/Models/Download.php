<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Observers\DownloadObserver;

class Download extends Model
{
    protected $table = 'downloads';

    protected static function boot()
    {
        parent::boot();
        static::observe(DownloadObserver::class);
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
