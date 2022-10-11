<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationCodeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'code' => $this->code,
            'label' => $this->label,

            'instrument' => InstrumentResource::make($this->whenLoaded('instrument'))
        ];
    }
}
