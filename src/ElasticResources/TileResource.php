<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\ElasticResources\Tile\ThemeResource as TileThemeResource;

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
            'themes' => TileThemeResource::many($this->themes)
        ];
    }
}
