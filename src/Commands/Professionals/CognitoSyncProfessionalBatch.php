<?php

namespace Vng\EvaCore\Commands\Professionals;

use Illuminate\Database\Eloquent\Collection;
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
            return 1;
        }

        $professionals = $this->getProfessionals();
        $this->getOutput()->writeln($professionals->count() . ' professionals found');
        foreach ($professionals as $professional) {
            $this->getOutput()->write('.');
            $this->getOutput()->writeln($professional->last_seen_at);
            $this->syncProfessional($professional);
        }

        $this->output->writeln('syncing professional batch finished');
        return 0;
    }

    public function getProfessionals(): Collection|array
    {
        /** @var ProfessionalRepositoryInterface $professionalRepo */
        $professionalRepo = app(ProfessionalRepositoryInterface::class);
        return $professionalRepo->getLastSeenProfessionals(20);
    }

    public function syncProfessional(Professional $professional)
    {
        CognitoService::make($professional->environment)->syncProfessional($professional);
    }
}
