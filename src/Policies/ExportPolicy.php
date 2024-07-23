<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Export;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExportPolicy extends InstrumentPropertyPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return true;
//        return $user->managerCan('export.viewAny');
    }

    public function viewAll(IsManagerInterface $user)
    {
        return true;
//        return $user->managerCan('export.viewAll');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Export $export
     * @return mixed
     */
    public function view(IsManagerInterface $user, Export $export)
    {
        return true;
//        if ($export->hasOwner()
//            && $user->managerCan('export.organisation.view')
//            && $export->isUserMemberOfOwner($user)
//        ) {
//            return true;
//        }
//        return $user->managerCan('export.view') || $this->viewAll($user);
    }

    public function create(IsManagerInterface $user)
    {
        return true;
//        return $user->managerCan('export.organisation.create')
//            || $user->managerCan('export.create');
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param Export $export
     * @return mixed
     */
    public function delete(IsManagerInterface $user, Export $export)
    {
        return true;
//        if ($export->hasOwner()
//            && $user->managerCan('export.organisation.delete')
//            && $export->isUserMemberOfOwner($user)
//        ) {
//            return true;
//        }
//        return $user->managerCan('export.delete');
    }
}
