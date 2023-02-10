<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Neighbourhood;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\TargetGroup;
use Vng\EvaCore\Models\Tile;
use Vng\EvaCore\Models\Township;

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
    public function attachContact(IsManagerInterface $user, Instrument $instrument, Contact $contact): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachContact(IsManagerInterface $user, Instrument $instrument, Contact $contact): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyTargetGroup(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachTargetGroup(IsManagerInterface $user, Instrument $instrument, TargetGroup $targetGroup): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachTargetGroup(IsManagerInterface $user, Instrument $instrument, TargetGroup $targetGroup): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyTile(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachTile(IsManagerInterface $user, Instrument $instrument, Tile $tile): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachTile(IsManagerInterface $user, Instrument $instrument, Tile $tile): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyClientCharacteristic(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachClientCharacteristic(IsManagerInterface $user, Instrument $instrument, ClientCharacteristic $clientCharacteristic): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachClientCharacteristic(IsManagerInterface $user, Instrument $instrument, ClientCharacteristic $clientCharacteristic): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyAvailableRegion(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachAvailableRegion(IsManagerInterface $user, Instrument $instrument, Region $region): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachAvailableRegion(IsManagerInterface $user, Instrument $instrument, Region $region): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyAvailableTownship(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachAvailableTownship(IsManagerInterface $user, Instrument $instrument, Township $township): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachAvailableTownship(IsManagerInterface $user, Instrument $instrument, Township $township): bool
    {
        return $this->update($user, $instrument);
    }

    public function attachAnyAvailableNeighbourhood(IsManagerInterface $user, Instrument $instrument): bool
    {
        return $this->update($user, $instrument);
    }
    public function attachAvailableNeighbourhood(IsManagerInterface $user, Instrument $instrument, Neighbourhood $neighbourhood): bool
    {
        return $this->update($user, $instrument);
    }
    public function detachAvailableNeighbourhood(IsManagerInterface $user, Instrument $instrument, Neighbourhood $neighbourhood): bool
    {
        return $this->update($user, $instrument);
    }
}
