<?php

namespace Vng\EvaCore\ElasticResources\Shared;

class RatingResource extends \Vng\EvaCore\ElasticResources\RatingResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['professional']);
        unset($resource['email']);
        unset($resource['professional_email']);
        return $resource;
    }
}
