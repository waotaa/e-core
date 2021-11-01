<?php

namespace Vng\EvaCore\ElasticResources;

use Illuminate\Support\Str;

class ProviderResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'name' => $this->name,
            'slug' => (string) Str::slug($this->name),
            'contact' => ContactResource::one($this->contact),
            'address' => AddressResource::one($this->address),
        ];
    }
}
