<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\InstrumentTrackerCreateRequest;
use Vng\EvaCore\Http\Requests\InstrumentTrackerUpdateRequest;
use Vng\EvaCore\Models\InstrumentTracker;

interface InstrumentTrackerRepositoryInterface extends InstrumentOwnedEntityRepositoryInterface
{
    public function create(InstrumentTrackerCreateRequest $request): InstrumentTracker;
    public function update(InstrumentTracker $instrumentTracker, InstrumentTrackerUpdateRequest $request): InstrumentTracker;
}
