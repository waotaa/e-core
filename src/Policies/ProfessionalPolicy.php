<?php

namespace Vng\EvaCore\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Professional;

class ProfessionalPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user): bool
    {
        return $user->managerCan('viewAny professional');
    }

    public function view(IsManagerInterface $user, Professional $professional): bool
    {
        return $user->managerCan('view professional');
    }

    public function create(IsManagerInterface $user): bool
    {
        return $user->managerCan('create professional');
    }

    public function update(IsManagerInterface $user, Professional $professional): bool
    {
        return $user->managerCan('update professional');
    }

    public function delete(IsManagerInterface $user, Professional $professional): bool
    {
        return $user->managerCan('delete professional');
    }
}
