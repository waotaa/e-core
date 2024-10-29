<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Models\Download;

class DownloadResource extends ElasticResource
{
    /** @var Download */
    protected $resource;

    public function toArray()
    {
        return [
            'NaamDownload' => $this->label,
            'UrlDownload' => $this->cdnUrl,
            'Instrument' => InstrumentResource::many($this->whenLoaded('instruments')),
        ];
    }
}
