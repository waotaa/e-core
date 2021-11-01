<?php

namespace Vng\EvaCore\Console\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElastic;
use Vng\EvaCore\Jobs\SyncResourceToElastic;
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
            dispatch(new SyncResourceToElastic($tile));
        }

        foreach (Tile::onlyTrashed()->get() as $tile) {
            dispatch(new RemoveResourceFromElastic($tile));
        }

        $this->getOutput()->writeln('');
        $this->getOutput()->writeln('');
        return 0;
    }
}
