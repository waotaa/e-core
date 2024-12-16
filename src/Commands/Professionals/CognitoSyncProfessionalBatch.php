<?php

namespace Vng\EvaCore\Commands\Professionals;

use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Repositories\ProfessionalRepositoryInterface;
use Vng\EvaCore\Services\Cognito\CognitoService;

class CognitoSyncProfessionalBatch extends AbstractCognitoCommand
{
    protected $signature = 'professionals:sync-batch';
    protected $description = 'Sync the professionals with the AWS user pool';

    public function handle(): int
    {
        $this->output->writeln('syncing professional batch');

        if (!$this->hasValidConfig()) {
            $this->getOutput()->warning('invalid config');
            return 1;
        }

        $professionals = $this->getProfessionals();
        $this->getOutput()->writeln($professionals->count() . ' professionals found');
        foreach ($professionals as $professional) {
            $this->getOutput()->writeln('prof id: ' . $professional->id);
            $this->getOutput()->writeln('last seen: ' . ($professional->last_seen_at ?? 'never seen'));
            $this->syncProfessional($professional);
        }

        $this->output->writeln('syncing professional batch finished');
        return 0;
    }

    public function getProfessionals(): Collection|array
    {
        /** @var ProfessionalRepositoryInterface $professionalRepo */
        $professionalRepo = app(ProfessionalRepositoryInterface::class);
        $query = $professionalRepo
            ->builder()
            ->whereHas('environment');

        return $professionalRepo->addLastSeenConditions($query, 20)->get();
    }

    public function syncProfessional(Professional $professional)
    {
        $environment = $professional->environment;
        if (is_null($environment)) {
            $this->warn('no environment found on professional');
            Log::warning('Attempted to sync professional without environment ['. $professional->id .']');
            return;
        }

        $this->getOutput()->writeln('environment: ' . ($professional->environment?->name ?? 'none?!?'));

        try {
            CognitoService::make($environment)->syncProfessional($professional);
        } catch (CognitoIdentityProviderException $exception) {
            Log::error("Failed to sync professional; probably couldn't find userpool", [
                'exception' => $exception
            ]);
        }
    }
}
