<?php

namespace Vng\EvaCore\Commands\Professionals;

use Vng\EvaCore\Services\Cognito\CognitoService;
use Illuminate\Console\Command;

class CognitoSetup extends Command
{
    protected $signature = 'professionals:setup {--s|silent}';
    protected $description = 'Make sure the userpool and it\'s client are setup';

    public function handle(): int
    {
        CognitoService::ensureSetup();
        $this->call('professionals:get-config', ['--silent' => $this->option('silent')]);
        return 0;
    }
}
