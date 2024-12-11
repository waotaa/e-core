<?php

namespace Vng\EvaCore\Commands\Data;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Repositories\LocalPartyRepositoryInterface;
use Vng\EvaCore\Repositories\NationalPartyRepositoryInterface;
use Vng\EvaCore\Repositories\PartnershipRepositoryInterface;
use Vng\EvaCore\Repositories\RegionalPartyRepositoryInterface;

class CheckSoftDeletedOrganisations extends Command
{
    protected $signature = 'data:soft-deleted-organisations {--fix}';
    protected $description = 'Looks for soft-deleted organisation mismatches';

    public function __construct(
        protected LocalPartyRepositoryInterface $localPartyRepository,
        protected PartnershipRepositoryInterface $partnershipRepository,
        protected RegionalPartyRepositoryInterface $regionalPartyRepository,
        protected NationalPartyRepositoryInterface $nationalPartyRepository,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('Starting check for soft-deleted organisations...');

        $entities = [
            'LocalParty' => $this->localPartyRepository,
            'Partnership' => $this->partnershipRepository,
            'RegionalParty' => $this->regionalPartyRepository,
            'NationalParty' => $this->nationalPartyRepository,
        ];

        foreach ($entities as $entityName => $repository) {
            $this->processEntity($entityName, $repository);
        }

        $this->getOutput()->writeln('Check for soft-deleted organisations finished!');
        return 0;
    }

    /**
     * Process a single entity type.
     *
     * @param string $entityName
     * @param mixed $repository
     */
    protected function processEntity(string $entityName, mixed $repository): void
    {
        $this->getOutput()->writeln("Checking $entityName entries...");

        $entities = $repository->builderOnlyTrashed()
            ->whereHas('organisation', function (Builder $query) {
                $query->whereNull('deleted_at');
            })
            ->get();

        $this->getOutput()->info($entities->count() . " soft-deleted $entityName entries with active organisations found.");

        if ($entities->count() && app()->environment() !== 'production' && $this->confirm("See $entityName IDs?")) {
            $entities->each(fn ($entity) => $this->getOutput()->writeln($entity->id));
        }

        if ($this->option('fix')) {
            $this->fixEntities($entities);
        }
    }

    /**
     * Fix the deleted_at date for related organisations.
     *
     * @param Collection $entities
     */
    protected function fixEntities(Collection $entities): void
    {
        $entities->each(function ($entity) {
            $organisation = $entity->organisation;
            if ($organisation && is_null($organisation->deleted_at)) {
                $organisation->deleted_at = $entity->deleted_at;
                $organisation->save();
                $this->getOutput()->info("Updated Organisation ID {$organisation->id} with deleted_at: {$entity->deleted_at}");
            }
        });
    }
}
