<?php

namespace Vng\EvaCore\ElasticResources;

use Illuminate\Support\Str;

class ProviderResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => (string) Str::slug($this->name),

            // auxilary
            'import_mark' => $this->import_mark,

            // relations
            'organisation' => OrganisationResource::one($this->whenLoaded('organisation')),

            'address' => AddressResource::one($this->whenLoaded('address')),
            'contacts' => ContactResource::many($this->whenLoaded('contacts')),
        ];
    }
}
