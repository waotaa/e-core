<?php

namespace Vng\EvaCore\ElasticResources\SGR;

class ProviderResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'NaamAanbieder' => $this->name, // AN..200
            'UuidAanbieder' => $this->uuid, // AN36

            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
            'Adres' => AddressResource::one($this->whenLoaded('address')),
            'Contactpersoon' => ContactResource::many($this->whenLoaded('contacts')),
        ];
    }
}
