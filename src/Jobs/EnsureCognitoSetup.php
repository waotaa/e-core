<?php

namespace Vng\EvaCore\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Services\Cognito\CognitoService;

class EnsureCognitoSetup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Environment $environment)
    {}

    public function handle(): void
    {
        $environment = CognitoService::make($this->environment)->ensureSetup();
        $environment->saveQuietly();
    }
}
