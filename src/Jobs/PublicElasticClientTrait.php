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
}
