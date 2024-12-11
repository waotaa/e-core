<?php

namespace Vng\EvaCore\Commands\Data;

use Illuminate\Console\Command;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Repositories\ProfessionalRepositoryInterface;

class CheckLostProfessionals extends Command
{
    protected $signature = 'data:lost-professionals {--fix}';
    protected $description = 'Looks for professionals without an organisation';

    public function __construct(
        protected ProfessionalRepositoryInterface $professionalRepository,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('starting check lost professionals...');

        $professionals = $this->professionalRepository->builder()
            ->whereNull('environment_id')
            ->get();

        $this->getOutput()->info($professionals->count() . " without environment found");

        if ($professionals->count() && app()->environment() !== 'production' && $this->confirm("See ID's?")) {
            $professionals->each(fn (Professional $p) => $this->getOutput()->writeln($p->id));
        }

        if ($this->option('fix')) {
            $this->professionalRepository->builder()
                ->whereNull('environment_id')
                ->delete();
            $this->getOutput()->info('Professionals without environment have been deleted.');
        }

        $this->getOutput()->writeln('check lost professionals finished!');
        return 0;
    }
}
