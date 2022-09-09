<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Repositories\Eloquent\ManagerRepository;

class UserObserver
{
    public function creating(IsManagerInterface $user): void
    {
        (new ManagerRepository())->createForUser($user);
    }
}
