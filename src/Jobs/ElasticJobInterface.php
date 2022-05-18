<?php

namespace Vng\EvaCore\Jobs;

use Elasticsearch\Client;

interface ElasticJobInterface
{
    public function getClient(): Client;
}
