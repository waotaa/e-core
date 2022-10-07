<?php

namespace Vng\EvaCore\Commands\Professionals;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Services\Cognito\CognitoService;
use Illuminate\Console\Command;

class ProfessionalPasswordExpirationCheck extends Command
{
    protected $signature = 'professionals:password-expiration {environmentSlug?';
    protected $description = 'Reset passwords if expired';

    public function handle(): int
    {
        $this->getOutput()->writeln('resetting expired passwords...');

        if ($this->hasArgument('environmentSlug')) {
            $slug = $this->argument('environmentSlug');
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

        $this->getOutput()->writeln('resetting expired passwords finished');
        return 0;
    }

    public function resetExpiredPasswords(Environment $environment)
    {
        $this->output->writeln('handling environment ' . $environment->name . ' with userpool name ' . $environment->deriveUserPoolName());
        CognitoService::make($environment)->resetExpiredPasswords();
    }
}
