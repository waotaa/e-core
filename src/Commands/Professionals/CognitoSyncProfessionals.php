<?php

namespace Vng\EvaCore\Commands\Professionals;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Services\Cognito\CognitoService;
use Illuminate\Console\Command;

class CognitoSyncProfessionals extends Command
{
    protected $signature = 'professionals:sync {environmentSlug?} {--d|destructive}';
    protected $description = 'Sync the professionals with the AWS user pool';

    public function handle(): int
    {
        $this->output->writeln('syncing professionals');

        if ($this->hasArgument('environmentSlug')) {
            $slug = $this->argument('environmentSlug');
            $this->output->writeln('* for specified environment: ' . $slug);
            /** @var Environment $environment */
            $environment = Environment::query()->where('slug', $slug)->firstOrFail();
            $this->syncProfessionals($environment);
        } else {
            $environments = Environment::all();
            $environments->each(function (Environment $environment) {
                $this->syncProfessionals($environment);
            });
        }

        $this->output->writeln('syncing professionals finished');
        return 0;
    }

    public function syncProfessionals(Environment $environment)
    {
        $this->output->writeln('handling environment ' . $environment->name . ' with userpool name ' . $environment->deriveUserPoolName());
        $destructive = $this->option('destructive');
        CognitoService::make($environment)->syncProfessionals($destructive);
    }
}
