<?php

namespace Vng\EvaCore\Policies;

use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return true;
    }

    public function view(IsManagerInterface $user)
    {
        return true;
    }

    public function create(IsManagerInterface $user)
    {
        return false;
    }

    public function update(IsManagerInterface $user)
    {
        return false;
    }

    public function delete(IsManagerInterface $user)
    {
        return false;
    }

    // Attach user
    // Detach user
}
