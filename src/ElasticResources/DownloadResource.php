<?php

namespace Vng\EvaCore\ElasticResources;

class DownloadResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'label' => $this->label,
            'url' => $this->url,
            'filename' => $this->filename,
        ];
    }
}
