<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vng\EvaCore\ElasticResources\Both\ClientCharacteristicResource;

class ClientCharacteristic extends SearchableModel
{
    use SoftDeletes;

    protected $table = 'client_characteristics';
    protected string $elasticResource = ClientCharacteristicResource::class;

    protected $fillable = [
        'name',
        'code',
    ];

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'client_characteristic_instrument')
            ->using(ClientCharacteristicInstrument::class);
    }
}
