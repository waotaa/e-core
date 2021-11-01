<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\ElasticResources\Theme\TileResource as ThemeTileResource;

class ThemeResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'tiles' => ThemeTileResource::many($this->tiles)
        ];
    }
}
