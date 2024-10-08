<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class GetElasticsearchClusterSettings extends Command
{
    protected $signature = 'elastic:get-cluster-settings';
    protected $description = 'Retrieve the cluster settings from Elasticsearch';

    public function handle(): int
    {
        try {
            $response = ElasticsearchEndpointService::make()->getClusterSettings();

            // Toon de settings in JSON-indeling
            $this->info(json_encode($response, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            // Toon een foutmelding als er iets misgaat
            $this->error('Error fetching cluster settings: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
