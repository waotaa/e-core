<?php

namespace Vng\EvaCore\ElasticResources\Both;

use Vng\EvaCore\ElasticResources\Both\Township\RegionResource as TownshipRegionResource;

class TownshipResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdGemeente' => substr($this->code, 2, 4),
            'NaamGemeente' => $this->name,              // AN..200

            'Arbeidsmarktregio' => $this->region ? TownshipRegionResource::one($this->region) : null,

            // >> Current
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
