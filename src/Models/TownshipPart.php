<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TownshipPart extends Model
{
    protected $table = 'township_parts';

    protected $fillable = [
        'name',
    ];

    public function township(): BelongsTo
    {
        return $this->belongsTo(Township::class);
    }

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'available_township_part_instrument')
            ->withTimestamps()
            ->using(AvailableTownshipPartInstrument::class);
    }
}
