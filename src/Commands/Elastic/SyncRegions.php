<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Region;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\RegionRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncRegions extends Command
{
    protected $signature = 'elastic:sync-regions {--f|fresh}';
    protected $description = 'Sync all regions to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing regions');
        $this->getOutput()->writeln('');
        $index = 'regions';

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

        /** @var RegionRepositoryInterface $regionRepository */
        $regionRepository = app(RegionRepositoryInterface::class);
        $regions = $regionRepository
            ->builder()
            ->with([
                'townships'
            ])
            ->get();

        $this->output->writeln($regions->count() . ' regions found');
        $this->output->writeln('');

        foreach ($regions as $region) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $region->name);
            dispatch(new SyncSearchableModelToElasticJob($region));
        }

        foreach (Region::onlyTrashed()->get() as $region) {
            dispatch(new RemoveResourceFromElasticJob($region->getSearchIndex(), $region->getSearchId()));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing regions finished!');
        return 0;
    }
}
