<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Vng\EvaCore\Http\Requests\ProfessionalCreateRequest;
use Vng\EvaCore\Http\Requests\ProfessionalUpdateRequest;
use Vng\EvaCore\Models\Professional;

interface ProfessionalRepositoryInterface extends BaseRepositoryInterface
{
    public function create(ProfessionalCreateRequest $request): Professional;
    public function update(Professional $partnership, ProfessionalUpdateRequest $request): Professional;

    public function getLastSeenProfessionals($limit = 200, $daysAgoThreshold = null): Collection|array;
}
