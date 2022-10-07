<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\InstrumentType;

class InstrumentTypePolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('instrumentType.viewAny');
    }

    public function view(IsManagerInterface $user, InstrumentType $instrumentType)
    {
        return $user->managerCan('instrumentType.view');
    }

    public function create(IsManagerInterface $user)
    {
        return false;
//        return $user->can('instrumentType.create');
    }

    public function update(IsManagerInterface $user, InstrumentType $instrumentType)
    {
        return false;
//        return $user->can('instrumentType.update');
    }

    public function delete(IsManagerInterface $user, InstrumentType $instrumentType)
    {
        return false;
//        return $user->can('instrumentType.delete');
    }
}
