<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\ElasticClientBuilder;

class DeleteIndex extends Command
{
    protected $signature = 'elastic:delete-index {index} {--f|force}';
    protected $description = 'Delete a given index';

    public function handle(): int
    {
        $this->getOutput()->writeln('deleting index');

        $index = $this->argument('index');
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $index = $prefix . '-' . $index;
        }

        $force = $this->option('force');
        $confirmation = $force || $this->confirm('The index to delete is: '. $index . '. Is this correct?');
        if ($confirmation) {
            $elasticsearch = ElasticClientBuilder::make();

            $params = ['index' => $index];
            $exists = $elasticsearch->indices()->exists($params);
            if (!$exists) {
                $this->getOutput()->writeln('requested index does not exists');
                return 1;
            }
            $response = $elasticsearch->indices()->delete($params);

            if ($response['acknowledged']) {
                $this->getOutput()->writeln('successfully deleted');
            } else {
                $this->getOutput()->error('deletion failed');
            }
        }
        return 0;
    }
}
