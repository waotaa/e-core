<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Vng\EvaCore\Interfaces\IsOwnerInterface;

class OwnerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->resource instanceof IsOwnerInterface ? $this->resource->getOwnerType() : null,
            'name' => $this->name,
            'slug' => $this->slug,
            'organisation' => OrganisationResource::make($this->organisation)
        ];
    }
}
