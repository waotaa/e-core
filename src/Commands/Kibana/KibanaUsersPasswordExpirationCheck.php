<?php

namespace Vng\EvaCore\Commands\Kibana;

use Vng\EvaCore\Commands\EnvironmentArgumentTrait;
use Vng\EvaCore\Models\Environment;
use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\KibanaService;

class KibanaUsersPasswordExpirationCheck extends Command
{
    use EnvironmentArgumentTrait;

    protected $signature = 'kibana:password-expiration {environment?}';
    protected $description = 'Reset passwords of kibana users if expired';

    public function handle(): int
    {
        $this->getOutput()->writeln('resetting expired kibana passwords...');

        $environmentArgument = $this->argument('environment');
        $environments = $this->getTargetedEnvironments($environmentArgument);

        if ($environments->count() === 0) {
            $this->warn('No environments in set, aborting');
            return 1;
        }
        $this->line($environments->count() . ' environments in set');

        $environments->each(function (Environment $environment) {
            $this->resetExpiredPasswords($environment);
        });

        $this->getOutput()->writeln('resetting expired kibana passwords finished');
        return 0;
    }

    public function resetExpiredPasswords(Environment $environment)
    {
        $this->output->writeln('handling environment ' . $environment->name);
        $kibanaService = KibanaService::make($environment);
        if ($kibanaService->kibanaUserCredetialsAreExpired()) {
            $this->output->writeln('* expired');
        } else {
            $this->output->writeln('* NOT expired');
        }

        $kibanaService->resetKibanaCredetialsIfExpired();
    }
}
