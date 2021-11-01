<?php

namespace Vng\EvaCore\ElasticResources\Theme;

use Vng\EvaCore\ElasticResources\ElasticResource;

class TileResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name'  => $this->name,
            'sub_title'  => $this->sub_title,
            'description'  => $this->description,
            'list'  => $this->list,
            'key'  => $this->key,
            'position'  => $this->position,
        ];
    }
}
