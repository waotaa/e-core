<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Vng\EvaCore\Http\Resources\Township\RegionResource as TownshipRegionResource;

class TownshipResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' =>  $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'code' => $this->code,
            'featureId' => $this->featureId,
            'region' => $this->region ? TownshipRegionResource::make($this->region) : null,
        ];
    }
}
