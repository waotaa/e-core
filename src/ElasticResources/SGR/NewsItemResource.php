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

            'IndPublicatieNieuwsbericht' => Codelijsten::getJaNeeIndicatieCode($this->is_active),    // StdIndJN
            'DatBPublicatieNieuwsbericht' => $this->formatDate($this->publish_from),                 // DATUM
            'DatEPublicatieNieuwsbericht' => $this->formatDate($this->publish_to),                   // DATUM
            'DatPublicatieNieuwsbericht' => $this->formatDate($this->publish_from) ?: $this->formatDate($this->created_at),

            'Bewerkmoment' => [
                'DatAangemaakt' => $this->formatDate($this->created_at),    // DATUMTIJD
                'DatGewijzigd' => $this->formatDate($this->updated_at),     // DATUMTIJD
            ],

            'InstrumentOmgeving' => EnvironmentResource::one($this->environment),

        ];
    }
}
