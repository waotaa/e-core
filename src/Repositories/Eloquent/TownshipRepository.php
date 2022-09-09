<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Repositories\TownshipRepositoryInterface;

class TownshipRepository extends BaseRepository implements TownshipRepositoryInterface
{
    public string $model = Township::class;
}
