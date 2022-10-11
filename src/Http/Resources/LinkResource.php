<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'label' => $this->label,
            'url' => $this->url,

            'instrument' => InstrumentResource::make($this->whenLoaded('instrument'))
        ];
    }
}
