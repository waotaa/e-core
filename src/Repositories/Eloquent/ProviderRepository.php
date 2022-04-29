<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Repositories\OwnedEntityRepositoryInterface;

class ProviderRepository extends OwnedEntityRepository implements OwnedEntityRepositoryInterface
{
    public function all(): Collection
    {
        return Provider::all();
    }
}
