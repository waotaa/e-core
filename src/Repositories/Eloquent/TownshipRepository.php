<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Repositories\TownshipRepositoryInterface;

class TownshipRepository extends BaseRepository implements TownshipRepositoryInterface
{
    public string $model = Township::class;

    public function attachPartnerships(Township $township, string|array $partnershipIds): Township
    {
        $township->partnerships()->syncWithoutDetaching((array) $partnershipIds);
        return $township;
    }

    public function detachPartnerships(Township $township, string|array $partnershipIds): Township
    {
        $township->partnerships()->detach((array) $partnershipIds);
        return $township;
    }
}
