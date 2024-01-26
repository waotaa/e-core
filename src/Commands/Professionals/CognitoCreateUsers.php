<?php

namespace Vng\EvaCore\Commands\Professionals;

use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Services\Cognito\CognitoService;
use Illuminate\Console\Command;

class CognitoCreateUsers extends Command
{
    protected $signature = 'professionals:create-users {environmentSlug?} {--n|no-interaction}';
    protected $description = 'Create a cognito user for professional entities';

    public function handle(): int
    {
        $this->getOutput()->writeln('creating cognito users...');

        if (!$this->hasValidConfig()) {
            return 1;
        }

        $slug = $this->argument('environmentSlug');
        if (!is_null($slug)) {
            $this->output->writeln('* for specified environment: ' . $slug);
            /** @var Environment $environment */
            $environment = Environment::query()->where('slug', $slug)->firstOrFail();

            $professionals = Professional::query()->where('environment_id', $environment->id)->get();
            $professionals->each(function (Professional $professional) {
                $this->createCognitoUser($professional);
            });

        } else {
            $professionals = Professional::all();
            $professionals->each(function (Professional $professional) {
                $this->createCognitoUser($professional);
            });
        }

        $this->getOutput()->writeln('creating cognito users finished');
        return 0;
    }

    public function hasValidConfig(): bool
    {
        if (!CognitoService::hasRequiredConfig()) {
            $message = 'AWS Config missing: Could not create cognito users';
            Log::warning($message);
            $this->warn($message);
            return false;
        }
        return true;
    }

    public function createCognitoUser(Professional $professional)
    {
        $cognitoService = CognitoService::make($professional->environment);
        $profModel = $cognitoService->getUser($professional);
        if (is_null($profModel)) {
            $cognitoService->createProfessional($professional);
        }
    }
}
