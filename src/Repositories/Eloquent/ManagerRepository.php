<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;

class ManagerRepository extends BaseRepository implements ManagerRepositoryInterface
{
    public string $model = Manager::class;

    public function attachOrganisation(Organisation $organisation, Manager $manager): Manager
    {
        $manager->organisations()->syncWithoutDetaching($organisation->id);
        return $manager;
    }

    public function detachOrganisation(Manager $manager, Organisation $organisation): Manager
    {
        $manager->organisations()->detach($organisation->id);
        return $manager;
    }
}
