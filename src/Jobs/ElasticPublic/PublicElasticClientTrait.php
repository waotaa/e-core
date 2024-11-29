<?php

namespace Vng\EvaCore\Jobs\ElasticPublic;

use Elasticsearch\Client;
use Vng\EvaCore\Services\ElasticSearch\Clients\ElasticPublicClientBuilder;

trait PublicElasticClientTrait
{
    public function getClient(): Client
    {
        return ElasticPublicClientBuilder::make();
    }

    public static function prefixIndex($index): string
    {
        return 'stats-' . $index;
    }
}
