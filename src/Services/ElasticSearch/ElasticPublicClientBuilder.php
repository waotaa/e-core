<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

/**
 * Creates an elastic client for a second, public, elastic instance
 * Data on this instance is used to share insights on the kibana dashboards
 */
class ElasticPublicClientBuilder extends ClientBuilder
{
    private static $clientInstance = null;

    private function __construct()
    {
        $this
            ->setElasticCloudId(config('elastic.instances.public.cloud_id'))
            ->setBasicAuthentication(
                config('elastic.instances.public.username'),
                config('elastic.instances.public.password')
            );
    }

    public static function hasSettings()
    {
        return !is_null(config('elastic.instances.public.cloud_id'))
            && !is_null(config('elastic.instances.public.username'))
            && !is_null(config('elastic.instances.public.password'));
    }

    public static function make(): Client
    {
        if (is_null(self::$clientInstance)) {
            self::$clientInstance = (new self())->build();
        }
        return self::$clientInstance;
    }
}
