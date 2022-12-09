<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\RatingCreateRequest;
use Vng\EvaCore\Http\Requests\RatingUpdateRequest;
use Vng\EvaCore\Models\Rating;

interface RatingRepositoryInterface extends InstrumentOwnedEntityRepositoryInterface
{
    public function create(RatingCreateRequest $request): Rating;
    public function update(Rating $rating, RatingUpdateRequest $request): Rating;
}
