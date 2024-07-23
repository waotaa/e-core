<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Repositories\RegionRepositoryInterface;

class RegionRepository extends BaseRepository implements RegionRepositoryInterface
{
    public string $model = Region::class;

    public function allWithTownships(): Collection
    {
        return $this->builder()->with('townships')->get();
    }
}
