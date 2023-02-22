<?php

namespace Vng\EvaCore\Commands\Reallocation;

use Illuminate\Console\Command;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;
use Vng\EvaCore\Repositories\ProfessionalRepositoryInterface;

class MoveProfessionals extends Command
{
    // php artisan reallocate:clone-professionals {currentEnvironmentSlug} {newEnvironmentSlug}
    // php artisan reallocate:clone-professionals midden-holland-rmt midden-holland
    // php artisan reallocate:clone-professionals gooi-en-vechtstreek-rmt gooi-vechtstreek
    protected $signature = 'reallocate:clone-professionals {currentEnvironmentSlug} {newEnvironmentSlug}';
    protected $description = 'clones professionals of one environment to an other one';

    public function __construct(
        protected EnvironmentRepositoryInterface $environmentRepository,
        protected ProfessionalRepositoryInterface $professionalRepository,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->getOutput()->writeln('cloning professionals...');

        /** @var Environment $currentEnv */
        $currentEnv = $this->environmentRepository
            ->builder()
            ->where('slug', $this->argument('currentEnvironmentSlug'))
            ->firstOrFail();

        /** @var Environment $newEnv */
        $newEnv = $this->environmentRepository
            ->builder()
            ->where('slug', $this->argument('newEnvironmentSlug'))
            ->firstOrFail();

        $profs = $currentEnv->professionals;

        $profs->map(function (Professional $prof) use ($newEnv) {
            /** @var Professional $newProf */
            $newProf = $this->professionalRepository->new();
            $newProf->email = $prof->email;
            $newProf->environment()->associate($newEnv);
            $newProf->save();
            return $newProf;
        });

        $this->getOutput()->writeln('finished cloning professionals');

        return 0;
    }
}
