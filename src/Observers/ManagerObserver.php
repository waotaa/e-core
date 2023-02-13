<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Manager;

class ManagerObserver
{
    public function creating(Manager $manager): void
    {
        /** @var IsManagerInterface $creatingUser */
        $creatingUser = request()->user();
        $creatingManager = $creatingUser->manager()->get();

        if ($creatingManager) {
            $manager->createdBy()->associate($creatingManager);
        }
    }
}
