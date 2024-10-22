<?php

namespace Vng\EvaCore\ElasticResources\Original;

use Vng\EvaCore\ElasticResources\Original\Region\TownshipResource as RegionTownshipResource;
use Vng\EvaCore\Helpers\Codelijsten;

class RegionResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,

            'townships' => RegionTownshipResource::many($this->townships),
        ];
    }
}
