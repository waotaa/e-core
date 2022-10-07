<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ProfessionalCreateRequest;
use Vng\EvaCore\Http\Requests\ProfessionalUpdateRequest;
use Vng\EvaCore\Models\Professional;

interface ProfessionalRepositoryInterface extends BaseRepositoryInterface
{
    public function create(ProfessionalCreateRequest $request): Professional;
    public function update(Professional $partnership, ProfessionalUpdateRequest $request): Professional;
}
