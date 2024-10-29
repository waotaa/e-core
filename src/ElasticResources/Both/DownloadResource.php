<?php

namespace Vng\EvaCore\ElasticResources\Both;

use Vng\EvaCore\Models\Download;

class DownloadResource extends ElasticResource
{
    /** @var Download */
    protected $resource;

    public function toArray()
    {
        return [
            // >> SGR
            'NaamDownload' => $this->label,
            'UrlDownload' => $this->cdnUrl,
            'Instrument' => InstrumentResource::many($this->whenLoaded('instruments')),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'label' => $this->label,
            'url' => $this->cdnUrl,
            'filename' => $this->filename,

            'instrument' => InstrumentResource::many($this->whenLoaded('instruments')),
        ];
    }
}
