<?php

namespace Vng\EvaCore\ElasticResources\SGR\Shared;

class RatingResource extends \Vng\EvaCore\ElasticResources\SGR\RatingResource
{
    public function toArray(): array
    {
        $resource = parent::toArray();
        unset($resource['EmailadresAuteurBeoordeling']);
        return $resource;
    }
}
