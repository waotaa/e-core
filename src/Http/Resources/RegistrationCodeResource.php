<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationCodeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'label' => $this->label,

            'instrument' => InstrumentResource::make($this->instrument)
        ];
    }
}
