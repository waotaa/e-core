<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class GetMapping extends Command
{
    protected $signature = 'elastic:mapping {index}';
    protected $description = 'Shows elastic mapping';

    public function handle(): int
    {
        $this->getOutput()->writeln('getting mapping...');

        $index = $this->argument('index');
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $index = $prefix . '-' . $index;
        }

        if (!ElasticsearchEndpointService::make()->indexExists($index)) {
            $this->getOutput()->writeln('Requested index does not exist');
            return 1;
        }

        $mapping = ElasticsearchEndpointService::make()->getMapping($index);

        if ($mapping) {
            // Use json_encode with JSON_PRETTY_PRINT to display formatted output
            $this->getOutput()->writeln(json_encode($mapping, JSON_PRETTY_PRINT));
        } else {
            $this->getOutput()->writeln('No mapping found or an error occurred');
            return 1;
        }

        $this->getOutput()->writeln('getting mapping finished!');
        return 0;
    }
}
