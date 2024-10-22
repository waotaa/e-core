<?php

namespace Vng\EvaCore\ElasticResources\Both;

use Vng\EvaCore\Helpers\Codelijsten;

class RegistrationCodeResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'Registratiecode' => $this->code,       // AN..34
            'Registratiecodelabel' => $this->label, // AN..200
            'IndWeergeven' => Codelijsten::getJaNeeNvtIndicatieCode($this->is_displayed),

            'InstrumentWerknemersdienstverlening' => InstrumentResource::one($this->whenLoaded('instrument')),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'code' => $this->code,
            'label' => $this->label,
            'is_displayed' => $this->is_displayed,

            'instrument' => InstrumentResource::one($this->whenLoaded('instrument'))
        ];
    }
}
