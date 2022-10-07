<?php

namespace Vng\EvaCore\ElasticResources;

class ImplementationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'custom' => $this->custom,
        ];
    }
}
