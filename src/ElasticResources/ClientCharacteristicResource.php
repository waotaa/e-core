<?php

namespace Vng\EvaCore\ElasticResources;

class ClientCharacteristicResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
        ];
    }
}
