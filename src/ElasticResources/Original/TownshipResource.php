<?php

namespace Vng\EvaCore\ElasticResources\Original;

use Vng\EvaCore\ElasticResources\Original\Township\RegionResource as TownshipRegionResource;

class TownshipResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'name' =>  $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'code' => $this->code,

            'region' => $this->region ? TownshipRegionResource::one($this->region) : null,
        ];
    }
}
