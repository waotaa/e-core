<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\NationalPartyCreateRequest;
use Vng\EvaCore\Http\Requests\NationalPartyUpdateRequest;
use Vng\EvaCore\Models\NationalParty;

interface NationalPartyRepositoryInterface extends BaseRepositoryInterface, SoftDeletableRepositoryInterface
{
    public function create(NationalPartyCreateRequest $request): NationalParty;
    public function update(NationalParty $nationalParty, NationalPartyUpdateRequest $request): NationalParty;
}
