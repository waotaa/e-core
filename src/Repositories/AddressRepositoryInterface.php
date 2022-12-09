<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\AddressCreateRequest;
use Vng\EvaCore\Http\Requests\AddressUpdateRequest;
use Vng\EvaCore\Models\Address;

interface AddressRepositoryInterface extends OwnedEntityRepositoryInterface
{
    public function create(AddressCreateRequest $request): Address;
    public function update(Address $download, AddressUpdateRequest $request): Address;
}
