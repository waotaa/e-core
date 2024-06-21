<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\ElasticResources\EnvironmentResource;
use Vng\EvaCore\Jobs\ElasticPublic\RemoveResourceFromPublicElasticJob;
use Vng\EvaCore\Jobs\ElasticPublic\SyncResourceToPublicElasticJob;
use Vng\EvaCore\Models\Environment;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticPublicClientBuilder;

class SyncPublicEnvironments extends Command
{
    protected $signature = 'elastic:sync-public-environments {--f|fresh}';
    protected $description = 'Sync public environment resources to public ES instance';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing public environments');

        if ($this->option('fresh')) {
            $this->call(DeletePublicIndex::class, ['index' => 'environments', '--force' => true]);
        }

        if (!ElasticPublicClientBuilder::hasSettings()){
            $this->output->writeln('public instance settings missing');
            return 0;
        }

        $this->output->writeln('');

        /** @var EnvironmentRepositoryInterface $environmentRepo */
        $environmentRepo = app(EnvironmentRepositoryInterface::class);
        $environments = $environmentRepo
            ->builder()
            ->with([
                'contact',
                'featuredOrganisations',
                'organisation',
                'professionals'
            ])
            ->get();

        foreach ($environments as $environment) {
            $this->getOutput()->write('.');
            dispatch(new SyncResourceToPublicElasticJob(
                $environment,
                'environments',
                EnvironmentResource::class
            ));
        }

        foreach (Environment::onlyTrashed()->get() as $environment) {
            dispatch(new RemoveResourceFromPublicElasticJob(
                'environments',
                $environment->getSearchId()
            ));
        }

        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
