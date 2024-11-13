<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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
    public function attachAnyManager(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity): bool
    {
        Log::warning('Using policy on organisation entity. Use organisation instead / attachAnyManager');
        return $user->can('attachAnyManager', $organisationEntity->getOrganisation());
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @param Manager $targetManager
     * @return bool
     */
    public function attachManager(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity, Manager $targetManager): bool
    {
        Log::warning('Using policy on organisation entity. Use organisation instead / attachManager');
        return $user->can('attachManager', [$organisationEntity->getOrganisation(), $targetManager]);
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @param Manager $targetManager
     * @return bool
     */
    public function detachManager(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity, Manager $targetManager): bool
    {
        Log::warning('Using policy on organisation entity. Use organisation instead / detachManager');
        return $user->can('detachManager', [$organisationEntity->getOrganisation(), $targetManager]);
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @return bool
     */
    public function addInstrument(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity): bool
    {
        Log::warning('Using policy on organisation entity. Use organisation instead / addInstrument');
        return $user->can('addInstrument', $organisationEntity->getOrganisation());
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @return bool
     */
    public function addProvider(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity): bool
    {
        Log::warning('Using policy on organisation entity. Use organisation instead / addProvider');
        return $user->can('addProvider', $organisationEntity->getOrganisation());
    }

    /**
     * @param Model&IsManagerInterface $user
     * @param OrganisationEntityInterface $organisationEntity
     * @return bool
     */
    public function addContact(IsManagerInterface $user, OrganisationEntityInterface $organisationEntity): bool
    {
        Log::warning('Using policy on organisation entity. Use organisation instead / addContact');
        return $user->can('addContact', $organisationEntity->getOrganisation());
    }
}
