<?php

namespace Vng\EvaCore\ElasticResources\Both\Shared;

class RatingResource extends \Vng\EvaCore\ElasticResources\Both\RatingResource
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
