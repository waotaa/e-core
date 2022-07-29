<?php

namespace Vng\EvaCore\ElasticResources;

class LocalPartyResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'township' => TownshipResource::one($this->township),
        ];
    }
}
