<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class GetAllDocuments extends Command
{
    protected $signature = 'elastic:documents {index} {--e|exact}';
    protected $description = 'Retrieve all documents from the Elasticsearch index';

    public function handle(): int
    {
        $this->getOutput()->writeln('Retrieving all documents...');

        $index = $this->argument('index');

        $prefix = config('elastic.prefix');
        if ($prefix && !$this->option('exact')) {
            $this->output->writeln("used index-prefix: {$prefix}");
            $index = $prefix . '-' . $index;
        }
        $this->output->writeln("used index: {$index}");

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
            $this->getOutput()->writeln($document['id']);
//            $this->getOutput()->writeln(json_encode($document['source'], JSON_PRETTY_PRINT));
        }

        $this->output->writeln('');
        $this->output->writeln(count($documents) . ' documents found');
        $this->output->writeln('');

        $this->getOutput()->writeln('Document retrieval finished!');
        return 0;
    }
}


