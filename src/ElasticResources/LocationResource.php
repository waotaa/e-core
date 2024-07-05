<?php

namespace Vng\EvaCore\ElasticResources;

class LocationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdTypeUitvoeringslocatie' => $this->type, // todo: code van maken
            'IndUitvoeringslocatieActief' => $this->is_active,
            'NaamUitvoeringslocatie' => $this->name,
            'ToelUitvoeringslocatie' => $this->description,

            // todo: moet ik hier weten wat voor type adres het is?
            'Adres' => AddressResource::one($this->whenLoaded('address')),
            // todo: moet ik hier specificeren dat het een werknemersinstrument is?
            'Instrument' => InstrumentResource::one($this->whenLoaded('instrument')),

            // >> Current
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
