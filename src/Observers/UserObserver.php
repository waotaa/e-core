<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;

class UserObserver
{
    public function __construct(
        protected ManagerRepositoryInterface $managerRepository
    )
    {}

    public function creating(EvaUserInterface $user)
    {
        $user->assignRandomPassword();
    }

    public function created(IsManagerInterface $user): void
    {
        $this->managerRepository->createForUser($user);
    }

    public function deleted(IsManagerInterface $user): void
    {
        $this->managerRepository->delete($user->getManager()->id);
    }
}
