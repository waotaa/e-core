<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Support\Collection;

interface RegionRepositoryInterface extends BaseRepositoryInterface
{
    public function allWithTownships(): Collection;
}
