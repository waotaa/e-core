<?php

namespace Vng\EvaCore\ElasticResources;

class OrganisationResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
        ];
    }
}
