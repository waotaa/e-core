<?php

namespace Vng\EvaCore\Commands\Kibana;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Services\Cognito\CognitoService;
use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\KibanaService;

class KibanaUsersPasswordExpirationCheck extends Command
{
    protected $signature = 'kibana:password-expiration {environmentSlug?}';
    protected $description = 'Reset passwords of kibana users if expired';

    public function handle(): int
    {
        $this->getOutput()->writeln('resetting expired kibana passwords...');

        $slug = $this->argument('environmentSlug');
        if (!is_null($slug)) {
            $this->output->writeln('* for specified environment: ' . $slug);
            /** @var Environment $environment */
            $environment = Environment::query()->where('slug', $slug)->firstOrFail();
            $this->resetExpiredPasswords($environment);
        } else {
            $environments = Environment::all();
            $environments->each(function (Environment $environment) {
                $this->resetExpiredPasswords($environment);
            });
        }

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
