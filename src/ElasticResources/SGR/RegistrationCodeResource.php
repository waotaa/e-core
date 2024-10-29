<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class RegistrationCodeResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'Registratiecode' => $this->code,       // AN..34
            'Registratiecodelabel' => $this->label, // AN..200
            'IndWeergeven' => Codelijsten::getJaNeeIndicatieCode($this->resource->shouldDisplay()),

            'InstrumentWerknemersdienstverlening' => InstrumentResource::one($this->whenLoaded('instrument')),
        ];
    }
}
