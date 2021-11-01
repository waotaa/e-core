<?php

namespace Vng\EvaCore\ElasticResources\FormerStructure;

use Vng\EvaCore\ElasticResources\ElasticResource;

class RegioResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->naam,
            'slug' => $this->slug,
            'color' => $this->color,
        ];
    }
}
