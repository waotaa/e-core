<?php

namespace Vng\EvaCore\Policies;

use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return true;
    }

    public function view(IsManagerInterface $user, Contact $address)
    {
        return true;
    }

    public function create(IsManagerInterface $user)
    {
        return true;
    }

    public function update(IsManagerInterface $user, Contact $address)
    {
        return true;
    }

    public function delete(IsManagerInterface $user, Contact $address)
    {
        return true;
    }

    // attach instrument
    // detach instrument

    // attach provider
    // detach provider
}
