<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class GetIndexSettings extends Command
{
    protected $signature = 'elastic:get-index-settings {index}';
    protected $description = 'Retrieve the settings of an Elasticsearch index, including the field limit';

    public function handle(): int
    {
        $this->getOutput()->writeln('Retrieving index settings...');

        $index = $this->argument('index');
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $index = $prefix . '-' . $index;
        }

        // Controleer of de index bestaat
        if (!ElasticsearchEndpointService::make()->indexExists($index)) {
            $this->getOutput()->writeln("Index '$index' does not exist.");
            return 1;
        }

        // Haal de instellingen van de index op
        $settings = ElasticsearchEndpointService::make()->getIndexSettings($index);

        if (empty($settings)) {
            $this->getOutput()->writeln("Failed to retrieve settings for index '$index'.");
            return 1;
        }

        // Specifiek de field limit setting tonen
        $fieldLimit = $settings['index']['mapping']['total_fields']['limit'] ?? 'Not set';
        $this->getOutput()->writeln("Field limit for index '$index': $fieldLimit");

        // Haal de depth limit setting op
        $depthLimit = $settings['index']['mapping']['depth']['limit'] ?? 'Not set';
        $this->getOutput()->writeln("Depth limit for index '$index': $depthLimit");

        // Haal de nested fields limit setting op
        $nestedFieldsLimit = $settings['index']['mapping']['nested_fields']['limit'] ?? 'Not set';
        $this->getOutput()->writeln("Nested fields limit for index '$index': $nestedFieldsLimit");

//        var_dump($settings);
        return 0;
    }
}
