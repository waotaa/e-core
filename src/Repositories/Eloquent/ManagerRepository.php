<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;

class ManagerRepository extends BaseRepository implements ManagerRepositoryInterface
{
    public string $model = Manager::class;
}
