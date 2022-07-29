<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstrumentType extends Model
{
    protected $table = 'instrument_types';

    protected $fillable = [
        'name',
        'key',
    ];

    public function instruments(): HasMany
    {
        return $this->hasMany(Instrument::class);
    }
}
