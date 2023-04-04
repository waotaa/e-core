<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\RegionalParty;
use Vng\EvaCore\Models\Role;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Township;

class ManagerPolicy extends BasePolicy
{
    use HandlesAuthorization;

    private function hasManagingRelation(Manager $manager, Manager $targetManager)
    {
        $isSelf = $manager->id === $targetManager->id;
        $isCreatedBy = $targetManager->isCreatedBy($manager);
        return  $isSelf || $isCreatedBy || $manager->managersShareOrganisation($targetManager);
    }

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('manager.viewAny');
    }

    /**
     * @param Authorizable&IsManagerInterface $user
     * @param Manager $targetManager
     * @return bool
     */
    public function view(IsManagerInterface $user, Manager $targetManager)
    {
        $manager = $user->getManager();
        if ($manager->id === $targetManager->id || $targetManager->isCreatedBy($manager)) {
            return true;
        }
        if ($manager->managersShareOrganisation($targetManager)
            && $user->managerCan('manager.organisation.view')) {
            return true;
        }
        return $user->can('viewAll', Manager::class);
    }

    public function viewAll(IsManagerInterface $user)
    {
        return $user->managerCan('manager.view');
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('manager.create')
            || $user->managerCan('manager.organisation.create');
    }

    public function update(IsManagerInterface $user, Manager $targetManager)
    {
        $manager = $user->getManager();
        if ($manager->id === $targetManager->id || $targetManager->isCreatedBy($manager)) {
            return true;
        }
        if ($manager->managersShareOrganisation($targetManager)
            && $user->managerCan('manager.organisation.update')) {
            return true;
        }
        return $user->managerCan('manager.update');
    }

    public function delete(IsManagerInterface $user, Manager $targetManager)
    {
        $manager = $user->getManager();
        if ($manager->id === $targetManager->id || $targetManager->isCreatedBy($manager)) {
            return true;
        }
        if ($manager->managersShareOrganisation($targetManager)
            && $user->managerCan('manager.organisation.delete')) {
            return true;
        }
        return $user->managerCan('manager.delete');
    }

    public function restore(IsManagerInterface $user, Manager $targetManager)
    {
        $manager = $user->getManager();
        if ($manager->id === $targetManager->id || $targetManager->isCreatedBy($manager)) {
            return true;
        }
        if ($manager->managersShareOrganisation($targetManager)
            && $user->managerCan('manager.organisation.restore')) {
            return true;
        }
        return $user->managerCan('manager.restore');
    }

    public function forceDelete(IsManagerInterface $user, Manager $manager)
    {
        return $user->managerCan('manager.forceDelete');
    }

    public function attachAnyRole(IsManagerInterface $user, Manager $targetManager)
    {
        if ($this->hasManagingRelation($user->getManager(), $targetManager)
            && $user->managerCan('manager.organisation.role')) {
            return true;
        }
        return $user->managerCan('manager.role');
    }

    public function attachRole(IsManagerInterface $user, Manager $targetManager, Role $role)
    {
        if ($role->name === Role::SUPER_ADMIN_ROLE) {
            // Super admin role may not be assigned
            return false;
        }
        $manager = $user->getManager();
        if ($manager->isSuperAdmin()) {
            // User with super admin role may assign every role
            return true;
        }

        $assignableRoles = $manager->getAssignableRoles();

        if ($this->hasManagingRelation($manager, $targetManager)
            && $user->managerCan('manager.organisation.role')
            && in_array($role->name, $assignableRoles)
        ) {
            return true;
        }
        return $user->managerCan('manager.role');
    }

    public function detachRole(IsManagerInterface $user, Manager $targetManager)
    {
        if ($this->hasManagingRelation($user->getManager(), $targetManager)
            && $user->managerCan('manager.organisation.role')) {
            return true;
        }
        return $user->managerCan('manager.role');
    }


    public function attachAnyOrganisation(IsManagerInterface $user, Manager $manager)
    {
        return true;
    }

    public function attachOrganisation(IsManagerInterface $user, Manager $targetManager, Organisation $organisation)
    {
        $hasManagingRelation = $this->hasManagingRelation($user->getManager(), $targetManager);
        $managerIsMember = $user->getManager()->hasOrganisation($organisation);
        if ($hasManagingRelation
            && $managerIsMember
            && $user->managerCan('manager.organisation.members')
        ) {
            return true;
        }
        return $user->managerCan('manager.members');
    }

    public function detachOrganisation(IsManagerInterface $user, Manager $targetManager, Organisation $organisation)
    {
        $hasManagingRelation = $this->hasManagingRelation($user->getManager(), $targetManager);
        $managerIsMember = $user->getManager()->hasOrganisation($organisation);
        if ($hasManagingRelation
            && $managerIsMember
            && $user->managerCan('manager.organisation.members')
        ) {
            return true;
        }
        return $user->managerCan('manager.members');
    }


    public function attachAnyPartnership(IsManagerInterface $user, Manager $manager)
    {
        return true;
    }

    public function attachPartnership(IsManagerInterface $user, Manager $manager, Partnership $partnership)
    {
        return $this->attachOrganisation($user, $manager, $partnership->getOrganisation());
    }

    public function detachPartnership(IsManagerInterface $user, Manager $manager, Partnership $partnership)
    {
        return $this->detachOrganisation($user, $manager, $partnership->getOrganisation());
    }

    public function attachAnyLocalParty(IsManagerInterface $user, Manager $manager)
    {
        return true;
    }

    public function attachLocalParty(IsManagerInterface $user, Manager $manager, LocalParty $localParty)
    {
        return $this->attachOrganisation($user, $manager, $localParty->getOrganisation());
    }

    public function detachLocalParty(IsManagerInterface $user, Manager $manager, LocalParty $localParty)
    {
        return $this->detachOrganisation($user, $manager, $localParty->getOrganisation());
    }

    public function attachAnyRegionalParty(IsManagerInterface $user, Manager $manager)
    {
        return true;
    }

    public function attachRegionalParty(IsManagerInterface $user, Manager $manager, RegionalParty $regionalParty)
    {
        return $this->attachOrganisation($user, $manager, $regionalParty->getOrganisation());
    }

    public function detachRegionalParty(IsManagerInterface $user, Manager $manager, RegionalParty $regionalParty)
    {
        return $this->detachOrganisation($user, $manager, $regionalParty->getOrganisation());
    }

    public function attachAnyNationalParty(IsManagerInterface $user, Manager $manager)
    {
        return true;
    }

    public function attachNationalParty(IsManagerInterface $user, Manager $manager, NationalParty $nationalParty)
    {
        return $this->attachOrganisation($user, $manager, $nationalParty->getOrganisation());
    }

    public function detachNationalParty(IsManagerInterface $user, Manager $manager, NationalParty $nationalParty)
    {
        return $this->detachOrganisation($user, $manager, $nationalParty->getOrganisation());
    }
}
