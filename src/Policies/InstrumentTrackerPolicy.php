<?php

namespace Vng\EvaCore\Policies;

use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\InstrumentTracker;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstrumentTrackerPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(IsManagerInterface $user)
    {
        return true;
    }

    public function view(IsManagerInterface $user, InstrumentTracker $instrumentTracker)
    {
        return true;
    }

    public function create(IsManagerInterface $user)
    {
        return true;
    }

    public function update(IsManagerInterface $user, InstrumentTracker $instrumentTracker)
    {
        return true;
    }

    public function delete(IsManagerInterface $user, InstrumentTracker $instrumentTracker)
    {
        if ($instrumentTracker->voluntary) {
            return true;
        }
        return false;
    }
}
