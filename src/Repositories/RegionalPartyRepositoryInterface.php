<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\RegionalPartyCreateRequest;
use Vng\EvaCore\Http\Requests\RegionalPartyUpdateRequest;
use Vng\EvaCore\Models\RegionalParty;

interface RegionalPartyRepositoryInterface extends BaseRepositoryInterface
{
    public function create(RegionalPartyCreateRequest $request): RegionalParty;
    public function update(RegionalParty $regionalParty, RegionalPartyUpdateRequest $request): RegionalParty;
}
