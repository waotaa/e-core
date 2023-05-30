<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImplementationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'name' => $this->name,
            'code' => $this->code,
            'custom' => $this->custom,

            'instruments' => InstrumentResource::collection($this->whenLoaded('instruments'))
        ];
    }
}
