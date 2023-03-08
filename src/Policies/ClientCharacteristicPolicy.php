<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;

class ClientCharacteristicPolicy extends InstrumentPropertyPolicy
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
}
