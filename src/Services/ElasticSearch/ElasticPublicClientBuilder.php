<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticPublicClientBuilder extends ClientBuilder
{
    public function __construct()
    {
        $this
            ->setElasticCloudId(config('elastic.instances.public.cloud_id'))
            ->setBasicAuthentication(
                config('elastic.instances.public.username'),
                config('elastic.instances.public.password')
            );
    }

    public static function make(): Client
    {
        return (new self())->build();
    }
}
