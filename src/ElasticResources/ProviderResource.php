<?php

namespace Vng\EvaCore\ElasticResources;

use Illuminate\Support\Str;

class ProviderResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'NaamAanbieder' => $this->name, // AN..200
            'UuidAanbieder' => $this->uuid, // AN36

            'InstrumentBeherendeOrganisatie' => OrganisationResource::one($this->whenLoaded('organisation')),
            'Adres' => AddressResource::one($this->whenLoaded('address')),
            'Contactpersoon' => ContactResource::many($this->whenLoaded('contacts')),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => (string) Str::slug($this->name),

            // auxilary
            'import_mark' => $this->import_mark,

            // relations
            'organisation' => OrganisationResource::one($this->whenLoaded('organisation')),

//            'address' => AddressResource::one($this->whenLoaded('address')),
            'address' => AddressResource::one($this->address),
//            'contacts' => ContactResource::many($this->whenLoaded('contacts')),
            'contacts' => ContactResource::many($this->contacts),
        ];
    }
}
