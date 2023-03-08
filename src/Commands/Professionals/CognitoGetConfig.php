<?php

namespace Vng\EvaCore\Commands\Professionals;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Services\Cognito\UserPoolClientService;
use Vng\EvaCore\Services\Cognito\UserPoolService;
use Illuminate\Console\Command;

class CognitoGetConfig extends Command
{
    protected $signature = 'professionals:get-config {--environmentSlug} {--n|no-interaction}';
    protected $description = 'Get the id of the userpool and client';

    public function handle(): int
    {
        $slug = $this->argument('environmentSlug');
        /** @var Environment $environment */
        $environment = Environment::query()->where('slug', $slug)->firstOrFail();

        $userPool = UserPoolService::getUserPoolByEnvironment($environment);
        if ($userPool) {
            $this->output->writeln('UserPoolId: ' . $userPool->getId());
        } else {
            $this->output->warning('UserPool not found');
        }

        $client = UserPoolClientService::getUserPoolClientByEnvironment($environment);
        if ($client) {
            $this->output->writeln('UserPoolClientId: ' . $client->getClientId());
        } else {
            $this->output->warning('UserPoolClient not found');
        }

        if (!$this->option('no-interaction') && $this->confirm('See configuration?')) {
            $config = UserPoolService::describeUserPool($userPool->getId());
            $mfaConfig = UserPoolService::getUserPoolMfaConfig($userPool->getId());
            dd([
                $config,
                $mfaConfig
            ]);

        }
        return 0;
    }
}
