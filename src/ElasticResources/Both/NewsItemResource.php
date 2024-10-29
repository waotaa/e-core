<?php

namespace Vng\EvaCore\ElasticResources\Both;

use Vng\EvaCore\Helpers\Codelijsten;
use Vng\EvaCore\Models\NewsItem;
use Vng\EvaCore\Services\ModelHelpers\NewsItemHelper;

class NewsItemResource extends ElasticResource
{
    /** @var NewsItem */
    protected $resource;

    public function toArray()
    {
        return [
            // >> SGR
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

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),

            'publish_from' => $this->formatDate($this->publish_from),
            'publish_to' => $this->formatDate($this->publish_to),
            'publication_date' => $this->formatDate($this->publish_from) ?: $this->formatDate($this->created_at),
            'published' => NewsItemHelper::create($this->resource)->isPublished(),

            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'body' => $this->body,
            'teaser' => $this->teaser,

            'environment_slug' => $this->environment?->slug,
            'environment' => EnvironmentResource::one($this->environment)
        ];
    }
}
