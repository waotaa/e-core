<?php

namespace Vng\EvaCore\Http\Resources\Township;

use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
//            'description' => $this->description,
//            'color' => $this->color,
//            'cooperation_partners' => $this->cooperation_partners,
//            'townships' => $this->townships->pluck('name'),
        ];
    }
}
