<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $user->sendAccountCreationNotification();
    }
}
