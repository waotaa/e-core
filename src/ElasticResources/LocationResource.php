<?php

namespace Vng\EvaCore\ElasticResources;

class LocationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'name' => $this->name,
            'type' => [
                'key' => $this->rawType,
                'name' => $this->type,
            ],
            'is_active' => $this->is_active,
            'description' => $this->description,

            'address' => AddressResource::one($this->whenLoaded('address')),
            'instrument' => InstrumentResource::one($this->whenLoaded('instrument'))
        ];
    }
}
