<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Enums\LocationEnum;
use Vng\EvaCore\Observers\LocationObserver;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'description'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(LocationObserver::class);
    }

    public function setTypeAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['type'] = null;
            return;
        }
        $this->attributes['type'] = (new LocationEnum($value))->getKey();
    }

    public function getTypeAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        if(in_array($value, LocationEnum::keys())) {
            return LocationEnum::$value();
        }
        return $this->attributes['type'];
    }

    public function getRawTypeAttribute()
    {
        return $this->attributes['type'] ?? null;
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
