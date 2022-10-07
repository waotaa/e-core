<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\PartnershipCreateRequest;
use Vng\EvaCore\Http\Requests\PartnershipUpdateRequest;
use Vng\EvaCore\Models\Partnership;

interface PartnershipRepositoryInterface extends BaseRepositoryInterface, SoftDeletableRepositoryInterface
{
    public function create(PartnershipCreateRequest $request): Partnership;
    public function update(Partnership $partnership, PartnershipUpdateRequest $request): Partnership;

    public function attachTownships(Partnership $partnership, string|array $townshipIds): Partnership;
    public function detachTownships(Partnership $partnership, string|array $townshipIds): Partnership;
}
