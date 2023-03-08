<?php

namespace Vng\EvaCore\Commands\Professionals;

use Vng\EvaCore\Jobs\EnsureCognitoSetup;
use Vng\EvaCore\Models\Environment;
use Illuminate\Console\Command;

class CognitoSetup extends Command
{
    protected $signature = 'professionals:setup {environmentSlug?} {--n|no-interaction}';
    protected $description = 'Make sure the userpool and it\'s client are setup';

    public function handle(): int
    {
        $this->getOutput()->writeln('setting up user pools...');

        $slug = $this->argument('environmentSlug');
        if (!is_null($slug)) {
            $this->output->writeln('* for specified environment: ' . $slug);
            /** @var Environment $environment */
            $environment = Environment::query()->where('slug', $slug)->firstOrFail();
            $this->setupUserPool($environment);
        } else {
            $environments = Environment::all();
            $environments->each(function (Environment $environment) {
                $this->setupUserPool($environment);
            });
        }

        $this->getOutput()->writeln('setting up user pools finished');
        return 0;
    }

    public function setupUserPool(Environment $environment)
    {
        $this->output->writeln('handling environment ' . $environment->name . ' with userpool name ' . $environment->deriveUserPoolName());
        EnsureCognitoSetup::dispatch($environment);
//        $environment = CognitoService::make($environment)->ensureSetup();
//        $environment->saveQuietly();
    }
}
