<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class GetADocument extends Command
{
    protected $signature = 'elastic:a-document {index} {i?} {--e|exact}';
    protected $description = 'Retrieve the document from the Elasticsearch index';

    public function handle(): int
    {
        $this->getOutput()->writeln('Retrieving document...');

        $index = $this->argument('index');
        $i = $this->argument('i') ?? 0;

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
        $documentId = $documents[$i]['id'];

        $document = ElasticsearchEndpointService::make()->getDocument($index, $documentId);
        if (is_null($document)) {
            $this->getOutput()->writeln('Error retrieving document');
            return 1;
        }

//        // Definieer de array van gewenste eigenschappen
//        $desiredProperties = [
//            'publish',
//            'publish_from',
//            'publish_to',
//            'published',
//            'complete',
//        ];
//
//        $document = array_filter(
//            $document,
//            function ($key) use ($desiredProperties) {
//                return in_array($key, $desiredProperties);
//            },
//            ARRAY_FILTER_USE_KEY
//        );

        $this->getOutput()->writeln(json_encode($document, JSON_PRETTY_PRINT));
        $this->getOutput()->writeln('Document retrieved successfully!');
        return 0;
    }
}


