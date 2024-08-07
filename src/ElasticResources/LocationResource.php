<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Helpers\Codelijsten;

class LocationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdTypeUitvoeringslocatie' => Codelijsten::getUitvoeringLocatieCode($this->type),
            'IndUitvoeringslocatieActief' => $this->is_active,
            'NaamUitvoeringslocatie' => $this->name,
            'ToelUitvoeringslocatie' => $this->description,
            'Adres' => AddressResource::one($this->whenLoaded('address')),
            'InstrumentWerknemersdienstverlening' => InstrumentWerknemersdienstverleningResource::one($this->whenLoaded('instrument')),

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
            'instrument' => InstrumentWerknemersdienstverleningResource::one($this->whenLoaded('instrument'))
        ];
    }
}
