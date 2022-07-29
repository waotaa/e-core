<?php

namespace Vng\EvaCore\Commands\Format;

use Illuminate\Console\Command;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;
use Vng\EvaCore\Repositories\UserRepositoryInterface;

class MigrateToManagers extends Command
{
    protected $signature = 'format:migrate-managers';
    protected $description = 'Migrates to managers';

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
        $this->getOutput()->writeln('starting migrating to managers...');

        // create a manager entity for every user
        $users = $this->userRepository->builder()->whereDoesntHave('manager')->get();
//        $users = User::query()->whereDoesntHave('manager')->get();
        $users->each(function (EvaUserInterface $user) {
            $manager = $this->managerRepository->new();
            $manager->save();
            $user->manager()->associate($manager);
            $user->save();
        });

        $this->call(MigrateMembershipToPartyEntities::class);

        // All user associateables need to be migrated to manager - organisation relations
        // That manager can be linked to the organisation
        $this->call(MigrateMembersToOrganisations::class);

        $this->getOutput()->writeln('migrating to managers finished!');
        return 0;
    }
}
