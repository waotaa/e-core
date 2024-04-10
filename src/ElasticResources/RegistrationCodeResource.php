<?php

namespace Vng\EvaCore\ElasticResources;

class RegistrationCodeResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'code' => $this->code,
            'label' => $this->label,
            'is_displayed' => $this->is_displayed,

            'instrument' => InstrumentResource::one($this->instrument)
        ];
    }
}
