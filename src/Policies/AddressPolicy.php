<?php

namespace Vng\EvaCore\Policies;

use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return true;
    }

    public function view(IsManagerInterface $user, Address $address)
    {
        return true;
    }

    public function create(IsManagerInterface $user)
    {
        return true;
    }

    public function update(IsManagerInterface $user, Address $address)
    {
        return true;
    }

    public function delete(IsManagerInterface $user, Address $address)
    {
        return true;
    }

    // attach instrument
    // detach instrument
}
