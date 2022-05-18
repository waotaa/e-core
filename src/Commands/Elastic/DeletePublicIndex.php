<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Jobs\DeletePublicIndexJob;

class DeletePublicIndex extends Command
{
    protected $signature = 'elastic:delete-public-index {index} {--f|force}';
    protected $description = 'Delete a given index on the public instance';

    public function handle(): int
    {
        $this->getOutput()->writeln('deleting public index');

        $index = $this->argument('index');
        $force = $this->option('force');

        $prefix = config('elastic.prefix');
        $prefixedIndex = $prefix ? $prefix . '-' . $index : $index;

        $confirmation = $force || $this->confirm('The index to delete is: '. $prefixedIndex . '. Is this correct?');
        if ($confirmation) {
            DeletePublicIndexJob::dispatch($index);
        }

        $this->getOutput()->writeln('deleting public index finished!');
        return 0;
    }
}
