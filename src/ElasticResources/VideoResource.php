<?php

namespace Vng\EvaCore\ElasticResources;

class VideoResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'provider' => $this->provider,
            'video_identifier' => $this->video_identifier,
        ];
    }
}
