<?php

namespace Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToManagers;

use Illuminate\Console\Command;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;
use Vng\EvaCore\Repositories\UserRepositoryInterface;

class DeductManagerDataFromUser extends Command
{
    protected $signature = 'format:fill-managers';
    protected $description = 'Fills manager entities with data deducted from the user entities';

    protected UserRepositoryInterface $userRepository;
    protected ManagerRepositoryInterface $managerRepository;

    public function __construct(UserRepositoryInterface $userRepository, ManagerRepositoryInterface $managerRepository)
    {
        $this->userRepository = $userRepository;
        $this->managerRepository = $managerRepository;
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('starting filling managers...');

        $this->call(EnsureManagers::class);

        $users = $this->userRepository->all();
        $users->each(function (EvaUserInterface $user) {
            $manager = $user->manager;
            $manager->givenName = $manager->givenName ?? $user->getGivenName();
            $manager->surName = $manager->surName ?? $user->getSurName();
            $manager->email = $manager->email ?? $user->getEmail();
            $manager->save();
        });

        $this->getOutput()->writeln('filling managers finished!');
        return 0;
    }
}
