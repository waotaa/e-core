<?php

namespace Vng\EvaCore\Commands\Professionals;

use Vng\EvaCore\Services\Cognito\UserPoolClientService;
use Vng\EvaCore\Services\Cognito\UserPoolService;
use Illuminate\Console\Command;

class CognitoGetConfig extends Command
{
    protected $signature = 'professionals:get-config {--s|silent}';
    protected $description = 'Get the id of the userpool and client';

    public function handle(): int
    {
        $userpool = UserPoolService::getUserPool();
        if ($userpool) {
            $this->output->writeln('UserPoolId: ' . $userpool->getId());
        } else {
            $this->output->warning('UserPool not found');
        }

        $client = UserPoolClientService::getUserPoolClient();
        if ($client) {
            $this->output->writeln('UserPoolClientId: ' . $client->getClientId());
        } else {
            $this->output->warning('UserPoolClient not found');
        }

        if (!$this->option('silent') && $this->confirm('See configuration?')) {
            $config = UserPoolService::describeUserPool($userpool);
            $mfaConfig = UserPoolService::getUserPoolMfaConfig($userpool->getId());
            dd([
                $config,
                $mfaConfig
            ]);

        }
        return 0;
    }
}
