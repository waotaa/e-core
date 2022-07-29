<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Vng\EvaCore\Http\Requests\LocationCreateRequest;
use Vng\EvaCore\Http\Requests\LocationUpdateRequest;
use Vng\EvaCore\Models\Location;
use Vng\EvaCore\Repositories\LocationRepositoryInterface;

class LocationRepository extends BaseRepository implements LocationRepositoryInterface
{
    public function all(): Collection
    {
        return Location::all();
    }

    public function create(LocationCreateRequest $request): Location
    {
        // TODO: Implement create() method.
    }

    public function update(Location $location, LocationUpdateRequest $request): Location
    {
        // TODO: Implement update() method.
    }
}
