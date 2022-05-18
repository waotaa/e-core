<?php

namespace Vng\EvaCore\ElasticResources\Shared;

use Illuminate\Support\Str;
use Vng\EvaCore\ElasticResources\AddressResource;
use Vng\EvaCore\ElasticResources\ElasticResource;

class ProviderResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'name' => $this->name,
            'slug' => (string) Str::slug($this->name),
            'address' => AddressResource::one($this->address),
        ];
    }
}
