<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Vng\EvaCore\Models\Location;
use Vng\EvaCore\Repositories\EloquentRepositoryInterface;

class LocationRepository extends BaseRepository implements EloquentRepositoryInterface
{
    public function all(): Collection
    {
        return Location::all();
    }
}
