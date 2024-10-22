<?php

namespace Vng\EvaCore\ElasticResources\Original\Shared;

class RatingResource extends \Vng\EvaCore\ElasticResources\Original\RatingResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['EmailadresAuteurBeoordeling']);

        unset($resource['professional']);
        unset($resource['email']);
        unset($resource['professional_email']);
        return $resource;
    }
}
