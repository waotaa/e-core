<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\InstrumentCreateRequest;
use Vng\EvaCore\Http\Requests\InstrumentUpdateRequest;
use Vng\EvaCore\Models\Instrument;

interface InstrumentRepositoryInterface extends OwnedEntityRepositoryInterface
{
    public function create(InstrumentCreateRequest $request): Instrument;
    public function update(Instrument $instrument, InstrumentUpdateRequest $request): Instrument;
}
