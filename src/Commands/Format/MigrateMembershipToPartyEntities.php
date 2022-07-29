<?php

namespace Vng\EvaCore\Commands\Format;

use Vng\EvaCore\Models\Associateable;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Repositories\AssociateableRepositoryInterface;

class MigrateMembershipToPartyEntities extends AbstractMigrateToPartyEntities
{
    protected $signature = 'format:migrate-membership-to-parties';
    protected $description = 'Migrates membership to party entities';

    protected AssociateableRepositoryInterface $associateableRepository;

    public function __construct(AssociateableRepositoryInterface $associateableRepository)
    {
        $this->associateableRepository = $associateableRepository;
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('starting migrating membership to parties...');

        $this->migrateTownshipMemberships();
        $this->output->writeln('township membership migrated');

        $this->migrateRegionMemberships();
        $this->output->writeln('region membership migrated');

        $this->getOutput()->writeln('migrating membership to parties finished!');
        return 0;
    }

    public function migrateTownshipMemberships()
    {
        $associateables = $this->associateableRepository->builder()->whereHasMorph('association', ['township', Township::class])->get();
        $associateables->each(function (Associateable $associateable) {
            $association = $associateable->association;
            $localParty = $this->findOrCreateLocalParty($association);
            $associateable->association()->associate($localParty);
            $associateable->save();
        });
    }

    public function migrateRegionMemberships()
    {
        $associateables = $this->associateableRepository->builder()->whereHasMorph('association', ['region', Region::class])->get();
        $associateables->each(function (Associateable $associateable) {
            $association = $associateable->association;
            $regionalParty = $this->findOrCreateRegionalParty($association);
            $associateable->association()->associate($regionalParty);
            $associateable->save();
        });
    }
}
