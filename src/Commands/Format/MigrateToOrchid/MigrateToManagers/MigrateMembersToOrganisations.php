<?php

namespace Vng\EvaCore\Commands\Format\MigrateToOrchid\MigrateToManagers;

use Vng\EvaCore\Commands\Format\MigrateToOrchid\AbstractMigrateToPartyEntities;
use Vng\EvaCore\Interfaces\EvaUserInterface;
use Vng\EvaCore\Models\Associateable;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Repositories\UserRepositoryInterface;

class MigrateMembersToOrganisations extends AbstractMigrateToPartyEntities
{
    protected $signature = 'format:migrate-members-to-organisation';
    protected $description = 'Migrates membership from party entities to organisations';

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating members to organisations...');

        $users = $this->userRepository->all();
        $users->each(function (EvaUserInterface $user) {
            if($user->associateables->count()) {
                $organisations = $user->associateables
                    ->map(function (Associateable $associateable) {
                        $association = $associateable->association;
                        if ($association instanceof Township) {
                            $association = $this->findOrCreateLocalParty($association);
                        }
                        if ($association instanceof Region) {
                            $association = $this->findOrCreateRegionalParty($association);
                        }
                        return $association->organisation;
                    })
                    ->filter();

                /** @var Manager $manager */
                $manager = $user->manager;
                $manager->organisations()->syncWithoutDetaching($organisations->pluck('id'));
            }
        });

        $this->getOutput()->writeln('migrating members to organisations finished!');
        return 0;
    }
}
