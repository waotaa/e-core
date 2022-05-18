<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticClientBuilder extends ClientBuilder
{
    public function __construct()
    {
        $this
            ->setElasticCloudId(config('elastic.instances.default.cloud_id'))
            ->setBasicAuthentication(
                config('elastic.instances.default.username'),
                config('elastic.instances.default.password')
            );
    }

    public static function make(): Client
    {
        return (new self())->build();
    }
}
