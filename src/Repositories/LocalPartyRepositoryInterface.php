<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\LocalPartyCreateRequest;
use Vng\EvaCore\Http\Requests\LocalPartyUpdateRequest;
use Vng\EvaCore\Models\LocalParty;

interface LocalPartyRepositoryInterface extends BaseRepositoryInterface, SoftDeletableRepositoryInterface
{
    public function create(LocalPartyCreateRequest $request): LocalParty;
    public function update(LocalParty $localParty, LocalPartyUpdateRequest $request): LocalParty;
}
