<?php

namespace Vng\EvaCore\Commands\Format;

use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;
use Vng\EvaCore\Repositories\ProfessionalRepositoryInterface;

class AssociateProfessionalsWithEnvironment extends Command
{
    protected $signature = 'format:associate-professionals {--f|first}';
    protected $description = 'Associate all professionals with an environment (if only one exists)';

    public function __construct(
        protected EnvironmentRepositoryInterface $environmentRepository,
        protected ProfessionalRepositoryInterface $professionalRepository,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('starting associating professionals...');

        $environments = $this->environmentRepository->all();
        if (!$this->option('first') && $environments->count() !== 1) {
            $this->warn('More than one environment exists, skipping script');
            $this->info('Use the option --first to use the first environment for all professionals');
            $this->getOutput()->writeln('associating professionals skipped!');
            return 1;
        }

        $environment = $environments->first();

        $this->professionalRepository
            ->builder()
            ->update([
                'environment_id' => $environment->id
            ]);

        $this->getOutput()->writeln('associating professionals finished!');
        return 0;
    }


}
