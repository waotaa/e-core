<?php

namespace Vng\EvaCore\ElasticResources;

class RegistrationCodeResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'Registratiecode' => $this->code,
            'Registratiecodelabel' => $this->label,

            // todo: is_displayed toevoegen?

            'InstrumentWerknemersdienstverlening' => InstrumentWerknemersdienstverleningResource::one($this->whenLoaded('instrument')),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'code' => $this->code,
            'label' => $this->label,
            'is_displayed' => $this->is_displayed,

            'instrument' => InstrumentWerknemersdienstverleningResource::one($this->whenLoaded('instrument'))
        ];
    }
}
