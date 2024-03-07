<?php

namespace Vng\EvaCore\Observers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
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

    /**
     * @param EvaUserInterface&IsManagerInterface $user
     * @return void
     */
    public function created(EvaUserInterface $user): void
    {
        $this->managerRepository->createForUser($user);
        if (!App::runningUnitTests()) {
            $user->sendAccountCreationNotification();
        }
    }

    public function deleted(IsManagerInterface $user)
    {
        if (!in_array(SoftDeletes::class, class_uses_recursive(get_class($user)))) {
            // No softdeletes used, hard delete user
            $this->hardDelete($user);
        }
    }

    public function forceDeleted(IsManagerInterface $user)
    {
        $this->hardDelete($user);
    }

    protected function hardDelete(IsManagerInterface $user)
    {
        $manager = $user->getManager();
        if (!is_null($manager)) {
            $this->managerRepository->delete($manager->id);
        }
    }
}
