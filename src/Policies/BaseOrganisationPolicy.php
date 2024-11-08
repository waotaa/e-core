<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\HasMembersInterface;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Interfaces\OrganisationEntityInterface;
use Vng\EvaCore\Models\Manager;

abstract class BaseOrganisationPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @return bool
     */
    private function canAssignManagerToOrganisation(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity): bool
    {
        if ($user->managerCan('organisation.assign-manager.within-organisation')
            && $organisationEntity->hasMember($user)
        ) {
            return true;
        }
        return $user->managerCan('organisation.assign-manager');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @return bool
     */
    public function attachAnyManager(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity): bool
    {
        return $this->canAssignManagerToOrganisation($user, $organisationEntity);
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @param Manager $targetManager
     * @return bool
     */
    public function attachManager(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity, Manager $targetManager): bool
    {
        if ($user->getManager()->hasManagingRelation($targetManager)
            && $this->canAssignManagerToOrganisation($user, $organisationEntity)
        ) {
            return true;
        }
        return $user->managerCan('organisation.assign-manager');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @param Manager $targetManager
     * @return bool
     */
    public function detachManager(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity, Manager $targetManager): bool
    {
        if ($user->getManager()->hasManagingRelation($targetManager)
            && $this->canAssignManagerToOrganisation($user, $organisationEntity)
        ) {
            return true;
        }
        return $user->managerCan('organisation.assign-manager');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @return bool
     */
    public function addInstrument(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity): bool
    {
        if ($user->managerCan('instrument.organisation.create')
            && $organisationEntity->hasMember($user)
        ) {
            return true;
        }
        return $user->managerCan('instrument.create');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @return bool
     */
    public function addProvider(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity): bool
    {
        if ($user->managerCan('provider.organisation.create')
            && $organisationEntity->hasMember($user)
        ) {
            return true;
        }
        return $user->managerCan('provider.create');
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @return bool
     */
    public function addContact(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity): bool
    {
        if ($user->managerCan('contact.organisation.create')
            && $organisationEntity->hasMember($user)
        ) {
            return true;
        }
        return $user->managerCan('contact.create');
    }
}
