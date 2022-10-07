<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Repositories\RegionRepositoryInterface;

class RegionRepository extends BaseRepository implements RegionRepositoryInterface
{
    public string $model = Region::class;
}
