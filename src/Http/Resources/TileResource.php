<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name'  => $this->name,
            'sub_title'  => $this->sub_title,
            'excerpt'  => $this->excerpt,
            'description'  => $this->description,
            'crisis_description'  => $this->crisis_description,
            'crisis_services'  => $this->crisis_services,
            'list'  => $this->list,
            'key'  => $this->key,
            'position'  => $this->position,

            'instruments' => InstrumentResource::collection($this->whenLoaded('instruments'))
        ];
    }
}
