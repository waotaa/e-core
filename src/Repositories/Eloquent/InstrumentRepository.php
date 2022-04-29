<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Repositories\OwnedEntityRepositoryInterface;

class InstrumentRepository extends OwnedEntityRepository implements OwnedEntityRepositoryInterface
{
    public function all(): Collection
    {
        return Instrument::all();
    }
}
