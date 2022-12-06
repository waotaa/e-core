<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;

class NationalPartyObserver
{
    public function __construct(
        protected EnvironmentRepositoryInterface $environmentRepository
    )
    {}

    public function created(NationalParty $nationalParty): void
    {
        $environments = $this->environmentRepository->all();
        $nationalParty->getOrganisation()->featuringEnvironments()->syncWithoutDetaching($environments->pluck('id'));
        $nationalParty->save();
    }
}
