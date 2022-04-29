<?php

namespace Vng\EvaCore\ElasticResources;

class LocationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => [
                'key' => $this->rawType,
                'name' => $this->type,
            ],
            'is_active' => $this->is_active,
            'description' => $this->description,

            'address' => AddressResource::one($this->address),
        ];
    }
}
