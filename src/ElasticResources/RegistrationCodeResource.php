<?php

namespace Vng\EvaCore\ElasticResources;

class RegistrationCodeResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'Registratiecode' => $this->code,
            'RegistratiecodeLabel' => $this->label,

            // todo: is_displayed toevoegen?

            'Instrument' => InstrumentResource::one($this->whenLoaded('instrument')),

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
