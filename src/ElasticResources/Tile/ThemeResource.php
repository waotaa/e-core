<?php

namespace Vng\EvaCore\ElasticResources\Tile;

use Vng\EvaCore\ElasticResources\ElasticResource;

class ThemeResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'description' => $this->description
        ];
    }
}
