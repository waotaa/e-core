<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * To be removed when locations are fully implemented
 * Dont forget
 * the seeder
 * a migration to remove location_types and instrument_location_type
 */
class LocationType extends Model
{
    use SoftDeletes;

    protected $table = 'location_types';

    protected $fillable = [
        'name',
    ];

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'instrument_location_type')
            ->withTimestamps()
            ->using(InstrumentLocationType::class);
    }
}
