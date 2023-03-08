<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\LocationCreateRequest;
use Vng\EvaCore\Http\Requests\LocationUpdateRequest;
use Vng\EvaCore\Models\Location;

interface LocationRepositoryInterface extends InstrumentOwnedEntityRepositoryInterface
{
    public function create(LocationCreateRequest $request): Location;
    public function update(Location $location, LocationUpdateRequest $request): Location;
}
