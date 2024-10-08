<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class CreateIndex extends Command
{
    protected $signature = 'elastic:create-index {name}';
    protected $description = 'Create an empty index in Elasticsearch';

    public function handle(): int
    {
        $this->getOutput()->writeln('Creating index...');

        $indexName = $this->argument('name');

        // Voeg een prefix toe als dat is geconfigureerd
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $indexName = $prefix . '-' . $indexName;
        }

        $this->output->writeln("used index-prefix: {$prefix}");
        $this->output->writeln("used index: {$indexName}");

        // Controleer of de index al bestaat
        if (ElasticsearchEndpointService::make()->indexExists($indexName)) {
            $this->getOutput()->writeln("Index '$indexName' already exists.");
            return 1;
        }

        $created = ElasticsearchEndpointService::make()->createIndex($indexName, [
            'index' => [
                'mapping' => [
                    'total_fields' => [
                        'limit' => config('elastic.field_limit', 2000),     // default 1000
                    ],
                    'depth' => [
                        'limit' => 20 // default 20
//                        'limit' => 30 // default 20
                    ],
                    'nested_fields' => [
                        'limit' => 50 // default 50
//                        'limit' => 200 // default 50
                    ],
                    'nested_objects' => [
                        'limit' => 1000 // default 1000
//                        'limit' => 4000 // default 1000
                    ]
                ]
            ]
        ]);

        if ($created) {
            $this->getOutput()->writeln("Index '$indexName' created successfully!");
            return 0;
        } else {
            $this->getOutput()->writeln("Failed to create index '$indexName'.");
            return 1;
        }
    }
}
