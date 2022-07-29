<?php

namespace Vng\EvaCore\Http\Resources\Township;

use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'color' => $this->color,
            'cooperation_partners' => $this->cooperation_partners,
            'townships' => $this->townships->pluck('name'),
        ];
    }
}
