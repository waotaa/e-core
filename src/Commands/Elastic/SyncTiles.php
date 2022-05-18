<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncResourceToElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Tile;
use Illuminate\Console\Command;

class SyncTiles extends Command
{
    protected $signature = 'elastic:sync-tiles {--f|fresh}';
    protected $description = 'Sync all tiles to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing tiles');
        $this->getOutput()->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'tiles', '--force' => true]);
        }

        $this->getOutput()->writeln('');
        foreach (Tile::all() as $tile) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $tile->name);
            dispatch(new SyncSearchableModelToElasticJob($tile));
        }

        foreach (Tile::onlyTrashed()->get() as $tile) {
            dispatch(new RemoveResourceFromElasticJob($tile->getSearchIndex(), $tile->getSearchId()));
        }

        $this->getOutput()->writeln('');
        $this->getOutput()->writeln('');
        return 0;
    }
}
