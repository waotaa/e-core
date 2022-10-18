<?php

namespace Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToManagers;

use Illuminate\Console\Command;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;
use Vng\EvaCore\Repositories\UserRepositoryInterface;

class EnsureManagers extends Command
{
    protected $signature = 'format:ensure-managers';
    protected $description = 'Ensures every user entity has a manager';

    protected UserRepositoryInterface $userRepository;
    protected ManagerRepositoryInterface $managerRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ManagerRepositoryInterface $managerRepository)
    {
        $this->userRepository = $userRepository;
        $this->managerRepository = $managerRepository;
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('starting ensuring managers...');

        $this->ensureUsers();
        $this->output->writeln('users have manager');

        $this->removeObsoleteManagers();
        $this->output->writeln('deleted obsolete managers');

        $this->getOutput()->writeln('ensuring managers finished!');
        return 0;
    }

    private function ensureUsers()
    {
        // create a manager entity for every user
        $users = $this->userRepository->builder()->whereDoesntHave('manager')->get();
        $users->each(function (IsManagerInterface $user) {
            $this->managerRepository->createForUser($user);
        });
    }

    private function removeObsoleteManagers()
    {
        $ids = $this->getActiveManagerIds();
        $query = $this->managerRepository->builder();
        $query->whereNotIn('id', $ids)->delete();
    }

    private function getActiveManagerIds()
    {
        $users = $this->userRepository->all();
        return $users->pluck('manager_id')->toArray();
    }
}
