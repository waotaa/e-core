<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
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

    public function viewAny(IsManagerInterface $user)
    {
        return $user->managerCan('manager.viewAny');
    }

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
        $manager = $user->getManager();
        if ($manager->managersShareOrganisation($targetManager)
            && $user->managerCan('manager.organisation.role.manage')) {
            return true;
        }
        return $user->managerCan('manager.role.manage');
    }

    public function attachRole(IsManagerInterface $user, Manager $targetManager, Role $role)
    {
        if ($role->name === Role::SUPER_ADMIN_ROLE) {
            return false;
        }
        $manager = $user->getManager();
        if ($manager->isSuperAdmin()) {
            return true;
        }

        $assignableRoles = [];
        foreach ($manager->roles as $roleUser) {
            $assignable = Role::ASSIGNABLE_ROLES[$roleUser->name];
            $assignableRoles = array_unique(array_merge($assignableRoles, $assignable));
        }

        if ($manager->managersShareOrganisation($targetManager)
            && $user->managerCan('manager.organisation.role.manage')
            && in_array($role->name, $assignableRoles)
        ) {
            return true;
        }
        return $user->managerCan('manager.role.manage');
    }

    public function detachRole(IsManagerInterface $user, Manager $targetManager)
    {
        if ($user->getManager()->managersShareOrganisation($targetManager)
            && $user->managerCan('manager.organisation.role.manage')) {
            return true;
        }
        return $user->managerCan('manager.role.manage');
    }


    public function attachAnyOrganisation(IsManagerInterface $user, Manager $manager)
    {
        return true;
    }

    public function attachOrganisation(IsManagerInterface $user, Manager $manager, Organisation $organisation)
    {
        if ($user->getManager()->hasOrganisation($organisation)) {
            return true;
        }
        return $user->managerCan('manager.organisation.manage');
    }

    public function detachOrganisation(IsManagerInterface $user, Manager $manager, Organisation $organisation)
    {
        if ($user->getManager()->hasOrganisation($organisation)) {
            return true;
        }
        return $user->managerCan('manager.organisation.manage');
    }


    public function attachAnyPartnership(IsManagerInterface $user, Manager $manager)
    {
        return true;
    }

    public function attachPartnership(IsManagerInterface $user, Manager $manager, Partnership $partnership)
    {
        if ($user->getManager()->hasOrganisation($partnership->organisation)) {
            return true;
        }
        return $user->managerCan('manager.organisation.manage');
    }

    public function detachPartnership(IsManagerInterface $user, Manager $manager, Partnership $partnership)
    {
        if ($user->getManager()->hasOrganisation($partnership->organisation)) {
            return true;
        }
        return $user->managerCan('manager.organisation.manage');
    }

    public function attachAnyLocalParty(IsManagerInterface $user, Manager $manager)
    {
        return true;
    }

    public function attachLocalParty(IsManagerInterface $user, Manager $manager, LocalParty $localParty)
    {
        if ($user->getManager()->hasOrganisation($localParty->organisation)) {
            return true;
        }
        return $user->managerCan('manager.organisation.manage');
    }

    public function detachLocalParty(IsManagerInterface $user, Manager $manager, LocalParty $localParty)
    {
        if ($user->getManager()->hasOrganisation($localParty->organisation)) {
            return true;
        }
        return $user->managerCan('manager.organisation.manage');
    }

    public function attachAnyRegionalParty(IsManagerInterface $user, Manager $manager)
    {
        return true;
    }

    public function attachRegionalParty(IsManagerInterface $user, Manager $manager, RegionalParty $regionalParty)
    {
        if ($user->getManager()->hasOrganisation($regionalParty->organisation)) {
            return true;
        }
        return $user->managerCan('manager.organisation.manage');
    }

    public function detachRegionalParty(IsManagerInterface $user, Manager $manager, RegionalParty $regionalParty)
    {
        if ($user->getManager()->hasOrganisation($regionalParty->organisation)) {
            return true;
        }
        return $user->managerCan('manager.organisation.manage');
    }

    public function attachAnyNationalParty(IsManagerInterface $user, Manager $manager)
    {
        return true;
    }

    public function attachNationalParty(IsManagerInterface $user, Manager $manager, NationalParty $nationalParty)
    {
        if ($user->getManager()->hasOrganisation($nationalParty->organisation)) {
            return true;
        }
        return $user->managerCan('manager.organisation.manage');
    }

    public function detachNationalParty(IsManagerInterface $user, Manager $manager, NationalParty $nationalParty)
    {
        if ($user->getManager()->hasOrganisation($nationalParty->organisation)) {
            return true;
        }
        return $user->managerCan('manager.organisation.manage');
    }
}
