<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Repositories\ProfessionalRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncProfessionals extends Command
{
    protected $signature = 'elastic:sync-professionals {--f|fresh}';
    protected $description = 'Sync all professionals to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing professionals');
        $this->getOutput()->writeln('');
        $index = 'professionals';

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

        /** @var ProfessionalRepositoryInterface $professionalRepository */
        $professionalRepository = app(ProfessionalRepositoryInterface::class);
        $professionals = $professionalRepository
            ->builder()
            ->with([
                'environment'
            ])
            ->get();

        $this->output->writeln($professionals->count() . ' professionals found');
        $this->output->writeln('');

        foreach ($professionals as $professional) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $professional->name);
            dispatch(new SyncSearchableModelToElasticJob($professional));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing professionals finished!');
        return 0;
    }
}

