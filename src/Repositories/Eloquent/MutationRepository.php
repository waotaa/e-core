<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Vng\EvaCore\Models\Mutation;
use Vng\EvaCore\Repositories\MutationRepositoryInterface;

class MutationRepository extends BaseRepository implements MutationRepositoryInterface
{
    public string $model = Mutation::class;
}
