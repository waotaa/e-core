<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vng\EvaCore\Observers\ImplementationObserver;

class Implementation extends Model
{
    use SoftDeletes;

    protected $table = 'implementations';

    protected $fillable = [
        'name',
        'custom'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(ImplementationObserver::class);
    }

    public function instruments(): HasMany
    {
        return $this->hasMany(Instrument::class);
    }

    public function getOwningInstrumentAttribute(): ?Instrument
    {
        if (!$this->custom) {
            return null;
        }
        /** @var ?Instrument $instrument */
        $instrument = $this->instruments()->orderBy('created_at', 'asc')->first();
        return $instrument;
    }
}
