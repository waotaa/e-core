<?php

namespace Vng\EvaCore\Commands\Professionals;

use Vng\EvaCore\Services\Cognito\CognitoService;
use Illuminate\Console\Command;
use Vng\EvaCore\Services\Cognito\UserPoolService;

class CognitoSetup extends Command
{
    protected $signature = 'professionals:setup {--n|no-interaction}';
    protected $description = 'Make sure the userpool and it\'s client are setup';

    public function handle(): int
    {
        $this->output->writeln('Userpool name: ' . UserPoolService::getUserPoolName());
        CognitoService::ensureSetup();
        $this->call('professionals:get-config', [
            '--no-interaction' => $this->option('no-interaction')
        ]);
        return 0;
    }
}
