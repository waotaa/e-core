<?php

namespace Vng\EvaCore\Commands\Professionals;

use Illuminate\Console\Command;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Services\Cognito\CognitoService;

class CognitoFetchProfessionals extends Command
{
    protected $signature = 'professionals:fetch {environmentSlug?}';
    protected $description = 'Find new users in the AWS user pool and save them';

    public function handle(): int
    {
        $this->getOutput()->writeln('fetching professionals...');

        $slug = $this->argument('environmentSlug');
        if (!is_null($slug)) {
            $this->output->writeln('* for specified environment: ' . $slug);
            /** @var Environment $environment */
            $environment = Environment::query()->where('slug', $slug)->firstOrFail();
            $this->fetchNewUsers($environment);
        } else {
            $environments = Environment::all();
            $environments->each(function (Environment $environment) {
                $this->fetchNewUsers($environment);
            });
        }

        $this->getOutput()->writeln('fetching professionals finished');
        return 0;
    }

    public function fetchNewUsers(Environment $environment)
    {
        $this->output->writeln('handling environment ' . $environment->name . ' with userpool name ' . $environment->deriveUserPoolName());
        CognitoService::make($environment)->fetchNewCognitoUsers();
    }
}
