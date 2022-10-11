<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'provider' => $this->provider,
            'video_identifier' => $this->video_identifier,

            'instrument' => InstrumentResource::make($this->instrument)
        ];
    }
}
