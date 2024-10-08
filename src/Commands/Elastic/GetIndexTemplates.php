<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class GetIndexTemplates extends Command
{
    protected $signature = 'elastic:get-index-templates {index?}';
    protected $description = 'Retrieve index template(s) from Elasticsearch. Optionally filter by a specific index name.';

    public function handle(): int
    {
        $this->getOutput()->writeln('Retrieving template(s)...');

        // Haal de optionele indexnaam op
        $indexName = $this->argument('index');

        // Roep de service-functie aan om de templates op te halen
        $templates = ElasticsearchEndpointService::make()->getTemplates($indexName);

        // Filter systeemtemplates eruit (templates die beginnen met '.')
        $templates = array_filter($templates, function ($templateName) {
            return !str_starts_with($templateName, '.');
        }, ARRAY_FILTER_USE_KEY);

        // Controleer of er een fout is
        if (isset($templates['error'])) {
            $this->getOutput()->writeln('Error retrieving templates: ' . $templates['error']);
            return 1;
        }

        if (empty($templates)) {
            $this->getOutput()->writeln($indexName
                ? "No template found for index '$indexName'."
                : "No templates found.");
            return 1;
        }

        // Toon de templates
        $this->getOutput()->writeln(json_encode($templates, JSON_PRETTY_PRINT));

        return 0;
    }
}
