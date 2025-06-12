<?php

namespace Vng\EvaCore\Commands\Kibana;

use Vng\EvaCore\Commands\EnvironmentArgumentTrait;
use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\KibanaService;

class KibanaUsersPasswordReset extends Command
{
    use EnvironmentArgumentTrait;

    protected $signature = 'kibana:password-reset {environment}';
    protected $description = 'Reset passwords of kibana user';

    public function handle(): int
    {
        $this->getOutput()->writeln('resetting kibana passwords...');

        $environmentArgument = $this->argument('environment');
        $environments = $this->getTargetedEnvironments($environmentArgument);

        if ($environments->count() !== 1) {
            $this->warn('No environment found, aborting');
            return 1;
        }
        $environment = $environments->first();

        $kibanaService = KibanaService::make($environment);

        if ($kibanaService->kibanaUserCredetialsAreExpired()) {
            $this->output->writeln('Credentials were expired');
        } else {
            $this->output->writeln('Credentials were not expired');
        }

        $kibanaService->resetKibanaCredentials();
        $this->output->info('Credentials reset');

        $this->getOutput()->writeln('resetting kibana password finished');
        return 0;
    }
}
