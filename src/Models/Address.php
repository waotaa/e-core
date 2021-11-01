<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'name',
        'straatnaam',
        'huisnummer',
        'postbusnummer',
        'antwoordnummer',
        'postcode',
        'woonplaats',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function instruments(): MorphToMany
    {
        return $this->morphedByMany(Instrument::class, 'addressable');
    }
}
