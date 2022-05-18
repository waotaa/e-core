<?php

namespace Vng\EvaCore\Jobs;

use Elasticsearch\Client;
use Vng\EvaCore\Services\ElasticSearch\ElasticPublicClientBuilder;

trait PublicElasticClientTrait
{
    public function getClient(): Client
    {
        return ElasticPublicClientBuilder::make();
    }

//    At this time we do prefix the public indexes as well
//    protected function getFullIndex(): string
//    {
//        return $this->index;
//    }
}
