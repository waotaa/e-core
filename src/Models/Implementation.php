<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vng\EvaCore\Observers\ImplementationObserver;

class Implementation extends Model
{
    use SoftDeletes;

    protected $table = 'implementations';

    protected $fillable = [
        'name',
        'code',
        'custom'
    ];

    protected $attributes = [
        'custom' => true,
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(ImplementationObserver::class);
    }

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'implementation_instrument')->using(ImplementationInstrument::class);
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
