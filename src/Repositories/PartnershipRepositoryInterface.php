<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\PartnershipCreateRequest;
use Vng\EvaCore\Http\Requests\PartnershipUpdateRequest;
use Vng\EvaCore\Models\Partnership;

interface PartnershipRepositoryInterface extends BaseRepositoryInterface
{
    public function create(PartnershipCreateRequest $request): Partnership;
    public function update(Partnership $partnership, PartnershipUpdateRequest $request): Partnership;
}
