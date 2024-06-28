<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Vng\EvaCore\Repositories\RegionRepositoryInterface;

class CacheableRegionRepository extends RegionRepository implements RegionRepositoryInterface
{
    public function all(): Collection
    {
        return Cache::remember('regions.all', 2*60, function () {
            return parent::all();
        });
    }
}
