<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Curl\Curl;
use Exception;
use Illuminate\Support\Str;

class ElasticCurl
{
    private Curl $instance;

    public function __construct()
    {
        $this->instance = new Curl();
        $this->setHeaders();
    }

    public static function make()
    {
        $c = new self();
        return $c->instance;
    }

    private function setHeaders()
    {
        $this->instance
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('kbn-xsrf', 'true');

        $apiKey = config('elastic.kibana.apiKey');
        if ($apiKey) {
            $this->instance->setHeader('Authorization', 'ApiKey ' . $apiKey);
        } else {
            throw new Exception('No API key provided');
        }
    }

    public static function getHost()
    {
        return Str::finish(config('elastic.kibana.host'), '/');
    }

    public static function getPathForEndpoint($endpoint)
    {
        if (Str::startsWith($endpoint, '/')) {
            $endpoint = substr($endpoint, 1);
        }
        return self::getHost() . $endpoint;
    }
}