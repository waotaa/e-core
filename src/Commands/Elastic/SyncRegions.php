<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElastic;
use Vng\EvaCore\Jobs\SyncResourceToElastic;
use Vng\EvaCore\Models\Region;
use Illuminate\Console\Command;

class SyncRegions extends Command
{
    protected $signature = 'elastic:sync-regions {--f|fresh}';
    protected $description = 'Sync all regions to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing regions');
        $this->getOutput()->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'regions', '--force' => true]);
        }

        $this->getOutput()->writeln('');
        foreach (Region::all() as $region) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $region->name);
            dispatch(new SyncResourceToElastic($region));
        }

        foreach (Region::onlyTrashed()->get() as $region) {
            dispatch(new RemoveResourceFromElastic($region));
        }

        $this->getOutput()->writeln('');
        $this->getOutput()->writeln('');
        return 0;
    }
}
