<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationType extends Model
{
    use SoftDeletes;

    protected $table = 'location_types';

    protected $fillable = [
        'name',
        'code',
    ];

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'instrument_location_type')
            ->withTimestamps()
            ->using(InstrumentLocationType::class);
    }
}
