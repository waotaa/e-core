<?php

namespace Vng\EvaCore\Commands\Elastic;

use Elasticsearch\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Vng\EvaCore\Services\ElasticSearch\ElasticClientBuilder;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class GetAllDocuments extends Command
{
    protected $signature = 'elastic:documents {index}';
    protected $description = 'Retrieve all documents from the Elasticsearch index';

    public function handle(): int
    {
        $this->getOutput()->writeln('Retrieving all documents...');

        $index = $this->argument('index');
        $prefix = config('elastic.prefix');
        $this->output->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($prefix) {
            $index = $prefix . '-' . $index;
        }

        if (!ElasticsearchEndpointService::make()->indexExists($index)) {
            $this->getOutput()->writeln('Requested index does not exist');
            return 1;
        }

        $documents = ElasticsearchEndpointService::make()->getAllDocuments($index);

        if (empty($documents)) {
            $this->getOutput()->writeln('No documents found or an error occurred.');
            return 1;
        }

        foreach ($documents as $document) {
            $this->getOutput()->writeln(json_encode($document, JSON_PRETTY_PRINT));
        }

        $this->getOutput()->writeln('Document retrieval finished!');
        return 0;
    }
}


