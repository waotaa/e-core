<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => [
                'key' => $this->rawType,
                'name' => $this->type,
            ],
            'is_active' => $this->is_active,
            'description' => $this->description,

            'address' => AddressResource::make($this->address),
            'instrument' => InstrumentResource::make($this->instrument),
        ];
    }
}
