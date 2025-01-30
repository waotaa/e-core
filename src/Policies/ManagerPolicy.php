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
        return $manager->hasManagingRelation($targetManager);
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
            || $user->managerCan('manager.create.within-organisation');
    }

    public function update(IsManagerInterface $user, Manager $targetManager)
    {
        $manager = $user->getManager();
        if ($manager->id === $targetManager->id || $targetManager->isCreatedBy($manager)) {
            return true;
        }

        $targetIsNoSuperAdmin = !$targetManager->hasRole(Role::SUPER_ADMIN_ROLE);
        if ($manager->managersShareOrganisation($targetManager)
            && $user->managerCan('manager.update.within-organisation')
            && $targetIsNoSuperAdmin
        ) {
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

        $targetIsNoSuperAdmin = !$targetManager->hasRole(Role::SUPER_ADMIN_ROLE);
        if ($manager->managersShareOrganisation($targetManager)
            && $user->managerCan('manager.delete.within-organisation')
            && $targetIsNoSuperAdmin
        ) {
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
        $targetIsNoSuperAdmin = !$targetManager->hasRole(Role::SUPER_ADMIN_ROLE);
        if ($manager->managersShareOrganisation($targetManager)
            && $user->managerCan('manager.restore.within-organisation')
            && $targetIsNoSuperAdmin
        ) {
            return true;
        }
        return $user->managerCan('manager.restore');
    }

    public function forceDelete(IsManagerInterface $user, Manager $manager)
    {
        return $user->managerCan('manager.forceDelete');
    }

    public function attachAnyRole(IsManagerInterface $user, Manager $targetManager): bool
    {
        if ($user->getManager()->hasManagingRelation($targetManager)
            && $user->managerCan('manager.assign-role.within-organisation')) {
            return true;
        }
        return $user->managerCan('manager.assign-role');
    }

    public function attachRole(IsManagerInterface $user, Manager $targetManager, Role $role): bool
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
        $targetIsNoSuperAdmin = !$targetManager->hasRole(Role::SUPER_ADMIN_ROLE);

        if ($user->getManager()->hasManagingRelation($targetManager)
            && $user->managerCan('manager.assign-role.within-organisation')
            && in_array($role->name, $assignableRoles)
            && $targetIsNoSuperAdmin
        ) {
            return true;
        }
        return $user->managerCan('manager.assign-role');
    }

    public function detachRole(IsManagerInterface $user, Manager $targetManager): bool
    {
        $targetIsNoSuperAdmin = !$targetManager->hasRole(Role::SUPER_ADMIN_ROLE);
        if ($user->getManager()->hasManagingRelation($targetManager)
            && $user->managerCan('manager.assign-role.within-organisation')
            && $targetIsNoSuperAdmin
        ) {
            return true;
        }
        return $user->managerCan('manager.assign-role');
    }


    private function canAssignOrganisationsToManager(IsManagerInterface $user, Manager $targetManager): bool
    {
        $targetIsNoSuperAdmin = !$targetManager->hasRole(Role::SUPER_ADMIN_ROLE);
        if ($user->getManager()->hasManagingRelation($targetManager)
            && $user->managerCan('manager.assign-organisation.within-organisation')
            && $targetIsNoSuperAdmin
        ) {
            return true;
        }
        return $user->managerCan('manager.assign-organisation');
    }

    public function attachAnyOrganisation(IsManagerInterface $user, Manager $targetManager): bool
    {
        return $this->canAssignOrganisationsToManager($user, $targetManager);
    }

    public function attachOrganisation(IsManagerInterface $user, Manager $targetManager, Organisation $organisation): bool
    {
        if ($user->getManager()->hasOrganisation($organisation)
            && $this->canAssignOrganisationsToManager($user, $targetManager)
        ) {
            return true;
        }
        return $user->managerCan('manager.assign-organisation');
    }

    public function detachOrganisation(IsManagerInterface $user, Manager $targetManager, Organisation $organisation): bool
    {
        if ($user->getManager()->hasOrganisation($organisation)
            && $this->canAssignOrganisationsToManager($user, $targetManager)
        ) {
            return true;
        }
        return $user->managerCan('manager.members');
    }


    public function attachAnyPartnership(IsManagerInterface $user, Manager $manager): bool
    {
        return $this->attachAnyOrganisation($user, $manager);
    }

    public function attachPartnership(IsManagerInterface $user, Manager $manager, Partnership $partnership): bool
    {
        return $this->attachOrganisation($user, $manager, $partnership->getOrganisation());
    }

    public function detachPartnership(IsManagerInterface $user, Manager $manager, Partnership $partnership): bool
    {
        return $this->detachOrganisation($user, $manager, $partnership->getOrganisation());
    }

    public function attachAnyLocalParty(IsManagerInterface $user, Manager $manager): bool
    {
        return $this->attachAnyOrganisation($user, $manager);
    }

    public function attachLocalParty(IsManagerInterface $user, Manager $manager, LocalParty $localParty): bool
    {
        return $this->attachOrganisation($user, $manager, $localParty->getOrganisation());
    }

    public function detachLocalParty(IsManagerInterface $user, Manager $manager, LocalParty $localParty): bool
    {
        return $this->detachOrganisation($user, $manager, $localParty->getOrganisation());
    }

    public function attachAnyRegionalParty(IsManagerInterface $user, Manager $manager): bool
    {
        return $this->attachAnyOrganisation($user, $manager);
    }

    public function attachRegionalParty(IsManagerInterface $user, Manager $manager, RegionalParty $regionalParty): bool
    {
        return $this->attachOrganisation($user, $manager, $regionalParty->getOrganisation());
    }

    public function detachRegionalParty(IsManagerInterface $user, Manager $manager, RegionalParty $regionalParty): bool
    {
        return $this->detachOrganisation($user, $manager, $regionalParty->getOrganisation());
    }

    public function attachAnyNationalParty(IsManagerInterface $user, Manager $manager): bool
    {
        return $this->attachAnyOrganisation($user, $manager);
    }

    public function attachNationalParty(IsManagerInterface $user, Manager $manager, NationalParty $nationalParty): bool
    {
        return $this->attachOrganisation($user, $manager, $nationalParty->getOrganisation());
    }

    public function detachNationalParty(IsManagerInterface $user, Manager $manager, NationalParty $nationalParty): bool
    {
        return $this->detachOrganisation($user, $manager, $nationalParty->getOrganisation());
    }
}
