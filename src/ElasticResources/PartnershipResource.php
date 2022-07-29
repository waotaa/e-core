<?php

namespace Vng\EvaCore\ElasticResources;

class PartnershipResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug
        ];
    }
}
