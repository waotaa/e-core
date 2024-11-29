<?php

namespace Vng\EvaCore\Services\ElasticSearch\Clients;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use function config;

/**
 * Creates an elastic client for the behaviour elastic instance
 * Data on this instance is registered from the portal to generate insights for the kibana dashboards
 */
class ElasticBehaviourClientBuilder extends ClientBuilder
{
    private static $clientInstance = null;

    private function __construct()
    {
        $this
            ->setElasticCloudId(config('elastic.instances.behaviour.cloud_id'))
            ->setBasicAuthentication(
                config('elastic.instances.behaviour.username'),
                config('elastic.instances.behaviour.password')
            );
    }

    public static function hasSettings()
    {
        return !is_null(config('elastic.instances.behaviour.cloud_id'))
            && !is_null(config('elastic.instances.behaviour.username'))
            && !is_null(config('elastic.instances.behaviour.password'));
    }

    public static function make(): Client
    {
        if (is_null(self::$clientInstance)) {
            self::$clientInstance = (new self())->build();
        }
        return self::$clientInstance;
    }
}
