<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncResourceToElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Tile;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\TileRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncTiles extends Command
{
    protected $signature = 'elastic:sync-tiles {--f|fresh}';
    protected $description = 'Sync all tiles to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing tiles');
        $this->getOutput()->writeln('');
        $index = 'tiles';

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => $index, '--force' => true]);
        }

        $fullIndex = $index;
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $this->output->writeln("used index-prefix: {$prefix}");
            $fullIndex = $prefix . '-' . $index;
        }
        $this->output->writeln("used index: {$fullIndex}");

        if (!ElasticsearchEndpointService::make()->indexExists($fullIndex)) {
            $this->call(CreateIndex::class, [
                'index' => $index
            ]);
        }

        /** @var TileRepositoryInterface $tileRepository */
        $tileRepository = app(TileRepositoryInterface::class);
        $tiles = $tileRepository
            ->builder()
            ->get();

        $this->output->writeln($tiles->count() . ' tiles found');
        $this->output->writeln('');

        foreach ($tiles as $tile) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $tile->name);
            dispatch(new SyncSearchableModelToElasticJob($tile));
        }

        foreach (Tile::onlyTrashed()->get() as $tile) {
            dispatch(new RemoveResourceFromElasticJob($tile->getSearchIndex(), $tile->getSearchId()));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing tiles finished!');
        return 0;
    }
}
