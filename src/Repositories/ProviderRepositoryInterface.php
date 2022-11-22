<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ProviderCreateRequest;
use Vng\EvaCore\Http\Requests\ProviderUpdateRequest;
use Vng\EvaCore\Models\Provider;

interface ProviderRepositoryInterface extends OwnedEntityRepositoryInterface, SoftDeletableRepositoryInterface
{
    public function create(ProviderCreateRequest $request): Provider;
    public function update(Provider $provider, ProviderUpdateRequest $request): Provider;

    public function attachContacts(Provider $provider, string|array $contactIds, ?string $type = null): Provider;
    public function detachContacts(Provider $provider, string|array $contactIds): Provider;
}
