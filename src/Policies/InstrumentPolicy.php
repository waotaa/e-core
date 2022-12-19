<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Instrument;

class InstrumentPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('instrument.viewAny');
    }

    public function viewAll(IsManagerInterface $user): bool
    {
        return $user->managerCan('instrument.viewAll');
    }

    public function view(IsManagerInterface $user, Instrument $instrument): bool
    {
//        return $user->managerCan('instrument.viewAny');

        if ($instrument->hasOwner()
            && $user->managerCan('instrument.organisation.view')
            && $instrument->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('instrument.view') || $this->viewAll($user);
    }

    public function create(IsManagerInterface $user): bool
    {
        return $user->managerCan('instrument.organisation.create')
            || $user->managerCan('instrument.create');
    }

    /**
     * @param IsManagerInterface&EvaUserInterface $user
     * @param Instrument $instrument
     * @return bool
     */
    public function update(IsManagerInterface $user, Instrument $instrument): bool
    {
        if ($instrument->hasOwner()
            && $user->managerCan('instrument.organisation.update')
            && $instrument->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('instrument.update');
    }

    /**
     * @param IsManagerInterface&EvaUserInterface $user
     * @param Instrument $instrument
     * @return bool
     */
    public function delete(IsManagerInterface $user, Instrument $instrument): bool
    {
        if ($instrument->hasOwner()
            && $user->managerCan('instrument.organisation.delete')
            && $instrument->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('instrument.delete');
    }

    /**
     * @param IsManagerInterface&EvaUserInterface $user
     * @param Instrument $instrument
     * @return bool
     */
    public function restore(IsManagerInterface $user, Instrument $instrument): bool
    {
        if ($instrument->hasOwner()
            && $user->managerCan('instrument.organisation.restore')
            && $instrument->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('instrument.restore');
    }

    /**
     * @param IsManagerInterface&EvaUserInterface $user
     * @param Instrument $instrument
     * @return bool
     */
    public function forceDelete(IsManagerInterface $user, Instrument $instrument): bool
    {
        if ($instrument->hasOwner()
            && $user->managerCan('instrument.organisation.forceDelete')
            && $instrument->isUserMemberOfOwner($user)
        ) {
            return true;
        }
        return $user->managerCan('instrument.forceDelete');
    }

//    All things you can do when you can edit
    public function attachAnyGroupForm(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachGroupForm(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachGroupForm(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyLocation(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachLocation(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachLocation(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyAddress(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachAddress(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachAddress(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }

    public function addRegistrationCode(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function addLink(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function addDownload(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function addVideo(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyContact(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachContact(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachContact(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyTargetGroup(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachTargetGroup(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachTargetGroup(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyTile(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachTile(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachTile(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyClientCharacteristic(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachClientCharacteristic(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachClientCharacteristic(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyRegion(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachRegion(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachRegion(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyTownship(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachTownship(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachTownship(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyNeighbourhood(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachNeighbourhood(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachNeighbourhood(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
}
