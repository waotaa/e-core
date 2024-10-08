<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class GetDocument extends Command
{
    protected $signature = 'elastic:document {index} {id}';
    protected $description = 'Retrieve a document from the Elasticsearch index';

    public function handle(): int
    {
        $this->getOutput()->writeln('Retrieving document...');

        $index = $this->argument('index');
        $id = $this->argument('id');
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $index = $prefix . '-' . $index;
        }

        if (!ElasticsearchEndpointService::make()->indexExists($index)) {
            $this->getOutput()->writeln('Requested index does not exist');
            return 1;
        }

        $document = ElasticsearchEndpointService::make()->getDocument($index, $id);
        if (is_null($document)) {
            $this->getOutput()->writeln('Error retrieving document');
            return 1;
        }

        $this->getOutput()->writeln(json_encode($document, JSON_PRETTY_PRINT));
        $this->getOutput()->writeln('Document retrieved successfully!');
        return 0;
    }
}


