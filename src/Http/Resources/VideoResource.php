<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'provider' => $this->provider,
            'video_identifier' => $this->video_identifier,

            'instrument' => InstrumentResource::make($this->instrument)
        ];
    }
}
