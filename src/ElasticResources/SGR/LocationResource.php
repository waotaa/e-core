<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;

class LocationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'CdTypeUitvoeringslocatie' => Codelijsten::getUitvoeringLocatieCode($this->type),
            'IndUitvoeringslocatieActief' => Codelijsten::getJaNeeIndicatieCode($this->is_active),  // StdIndJN
            'NaamUitvoeringslocatie' => $this->name,                                                // AN..200
            'ToelUitvoeringslocatie' => $this->description,                                         // AN..320
            'Adres' => AddressResource::one($this->whenLoaded('address')),
            'InstrumentWerknemersdienstverlening' => InstrumentResource::one($this->whenLoaded('instrument')),
        ];
    }
}
