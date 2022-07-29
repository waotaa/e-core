<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AreaInterfaceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'identifier' => $this->resource->getAreaIdentifier(),
            'name' => $this->resource->getName(),
            'type' => $this->resource->getType(),
        ];
    }
}
