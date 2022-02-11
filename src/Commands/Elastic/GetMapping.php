<?php

namespace Vng\EvaCore\Commands\Elastic;

use Elasticsearch\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

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

        /** @var Client $elasticsearch */
        $elasticsearch = App::make('elasticsearch');

        $params = ['index' => $index];
        $exists = $elasticsearch->indices()->exists($params);
        if (!$exists) {
            $this->getOutput()->writeln('requested index does not exists');
            return 1;
        }
        $response = $elasticsearch->indices()->getMapping($params);

        var_dump($response);

        $this->getOutput()->writeln('getting mapping finished!');
        return 0;
    }
}
