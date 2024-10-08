<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class ListIndexes extends Command
{
    protected $signature = 'elastic:list-indexes';
    protected $description = 'Retrieve a list of all indexes from the Elasticsearch instance';

    public function handle(): int
    {
        $this->getOutput()->writeln('Retrieving indexes...');

        // Haal de lijst van indexen op via je ElasticsearchEndpointService
        $indexes = ElasticsearchEndpointService::make()->getIndexes();

        if (empty($indexes)) {
            $this->getOutput()->writeln('No indexes found.');
            return 1;
        }

        // Toon de lijst van indexen
        foreach ($indexes as $index) {
            $this->getOutput()->writeln($index);
        }

        $this->getOutput()->writeln('Indexes retrieved successfully!');
        return 0;
    }
}


