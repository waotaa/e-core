<?php

namespace Vng\EvaCore\ElasticResources;

class RegistrationCodeResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'label' => $this->label,
        ];
    }
}
