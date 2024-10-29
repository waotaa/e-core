<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;
use Vng\EvaCore\Models\NewsItem;

class NewsItemResource extends ElasticResource
{
    /** @var NewsItem */
    protected $resource;

    public function toArray()
    {
        return [
            'TitelNieuwsbericht' => $this->title,
            'OndertitelNieuwsbericht' => $this->sub_title,
            'InhoudNieuwsbericht' => $this->body,
            'TeaserNieuwsbericht' => $this->teaser,

            'DatAangemaakt' => $this->formatDate($this->created_at),
            'DatGewijzigd' => $this->formatDate($this->updated_at),

            'IndPublicatie' => Codelijsten::getJaNeeIndicatieCode($this->is_active),    // StdIndJN
            'DatBPublicatie' => $this->formatDate($this->publish_from),                 // DATUM
            'DatEPublicatie' => $this->formatDate($this->publish_to),                   // DATUM

            'InstrumentOmgeving' => EnvironmentResource::one($this->environment),

        ];
    }
}
