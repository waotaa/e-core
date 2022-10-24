<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\Instrument;

class ClientCharacteristicPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return $this->can($user, 'clientCharacteristic.viewAny');
    }

    public function view(IsManagerInterface $user)
    {
        return $user->managerCan('clientCharacteristic.view');
    }

    public function create(IsManagerInterface $user)
    {
        return $user->managerCan('clientCharacteristic.create');
    }

    public function update(IsManagerInterface $user)
    {
        return $user->managerCan('clientCharacteristic.update');
    }

    public function delete(IsManagerInterface $user)
    {
        return $user->managerCan('clientCharacteristic.delete');
    }

    public function restore(IsManagerInterface $user)
    {
        return $user->managerCan('clientCharacteristic.restore');
    }

    public function forceDelete(IsManagerInterface $user)
    {
        return $user->managerCan('clientCharacteristic.forceDelete');
    }


    /**
     * @param IsManagerInterface&Authorizable $user
     * @param ClientCharacteristic $clientCharacteristic
     * @param Instrument $instrument
     * @return bool
     */
    public function attachInstrument(IsManagerInterface $user, ClientCharacteristic $clientCharacteristic, Instrument $instrument): bool
    {
        return $user->can('update', $instrument);
    }

    /**
     * @param IsManagerInterface&Authorizable $user
     * @param ClientCharacteristic $clientCharacteristic
     * @param Instrument $instrument
     * @return bool
     */
    public function detachInstrument(IsManagerInterface $user, ClientCharacteristic $clientCharacteristic, Instrument $instrument): bool
    {
        return $user->can('update', $instrument);
    }
}
