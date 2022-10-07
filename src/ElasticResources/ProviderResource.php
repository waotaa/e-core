<?php

namespace Vng\EvaCore\ElasticResources;

use Illuminate\Support\Str;

class ProviderResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => (string) Str::slug($this->name),

            // auxilary
            'import_mark' => $this->import_mark,

            // relations
            'owner' => OwnerResource::one($this->owner), // depricated
            'organisation' => OrganisationResource::one($this->organisation),

            'address' => AddressResource::one($this->address),
            'contact' => ContactResource::one($this->contact),
        ];
    }
}
