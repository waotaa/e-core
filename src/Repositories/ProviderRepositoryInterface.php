<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ProviderCreateRequest;
use Vng\EvaCore\Http\Requests\ProviderUpdateRequest;
use Vng\EvaCore\Models\Provider;

interface ProviderRepositoryInterface extends BaseRepositoryInterface
{
    public function create(ProviderCreateRequest $request): Provider;
    public function update(Provider $provider, ProviderUpdateRequest $request): Provider;
}
