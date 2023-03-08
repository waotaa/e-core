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
        return $user->managerCan('professional.viewAny');
    }

    public function view(IsManagerInterface $user, Professional $professional): bool
    {
        return $user->managerCan('professional.view');
    }

    public function create(IsManagerInterface $user): bool
    {
        return $user->managerCan('professional.create');
    }

    public function update(IsManagerInterface $user, Professional $professional): bool
    {
        return $user->managerCan('professional.update');
    }

    public function delete(IsManagerInterface $user, Professional $professional): bool
    {
        return $user->managerCan('professional.delete');
    }
}
