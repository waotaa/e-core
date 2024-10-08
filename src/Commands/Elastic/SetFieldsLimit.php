<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SetFieldsLimit extends Command
{
    protected $signature = 'elastic:set-fields-limit {index} {--limit=1000}';
    protected $description = 'Set the total fields limit for an Elasticsearch index';

    public function handle(): int
    {
        $this->getOutput()->writeln('Setting fields limit...');

        $index = $this->argument('index');
        $limit = $this->option('limit');
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $index = $prefix . '-' . $index;
        }

        $this->output->writeln("used index-prefix: {$prefix}");
        $this->output->writeln("used index: {$index}");

        if (!ElasticsearchEndpointService::make()->indexExists($index)) {
            $this->getOutput()->writeln('Requested index does not exist');
            return 1;
        }

        $response = ElasticsearchEndpointService::make()->updateIndexSettings($index, [
            'index.mapping.total_fields.limit' => (int) $limit
        ]);

        if ($response) {
            $this->getOutput()->writeln("Fields limit for index '$index' set to $limit.");
            return 0;
        } else {
            $this->getOutput()->writeln('Failed to set fields limit.');
            return 1;
        }
    }
}

