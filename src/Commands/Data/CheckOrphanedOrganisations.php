<?php

namespace Vng\EvaCore\Commands\Data;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\LocalPartyRepositoryInterface;
use Vng\EvaCore\Repositories\NationalPartyRepositoryInterface;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;
use Vng\EvaCore\Repositories\PartnershipRepositoryInterface;
use Vng\EvaCore\Repositories\RegionalPartyRepositoryInterface;

class CheckOrphanedOrganisations extends Command
{
    protected $signature = 'data:orphaned-organisations {--fix : Remove orphaned organisations}';
    protected $description = 'Looks for orphaned organisations';

    public function __construct(
        protected OrganisationRepositoryInterface $organisationRepository,
        protected LocalPartyRepositoryInterface $localPartyRepository,
        protected PartnershipRepositoryInterface $partnershipRepository,
        protected RegionalPartyRepositoryInterface $regionalPartyRepository,
        protected NationalPartyRepositoryInterface $nationalPartyRepository,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('Starting check for orphaned organisations...');

        $organisations = $this->organisationRepository->builderWithTrashed()->get();
        $orphanedOrganisations = $organisations->filter(function ($organisation) {
            return !$this->isOrganisationLinked($organisation->id);
        });

        if ($orphanedOrganisations->isEmpty()) {
            $this->getOutput()->writeln('No orphaned organisations found.');
        } else {
            $this->displayOrphanedOrganisations($orphanedOrganisations);

            if ($this->option('fix')) {
                $this->deleteOrphanedOrganisations($orphanedOrganisations);
            }
        }

        $this->getOutput()->writeln('Check for orphaned organisations finished!');
        return 0;
    }

    protected function isOrganisationLinked($organisationId): bool
    {
        return $this->localPartyRepository->builderWithTrashed()->where('organisation_id', $organisationId)->exists()
            || $this->regionalPartyRepository->builderWithTrashed()->where('organisation_id', $organisationId)->exists()
            || $this->nationalPartyRepository->builderWithTrashed()->where('organisation_id', $organisationId)->exists()
            || $this->partnershipRepository->builderWithTrashed()->where('organisation_id', $organisationId)->exists();
    }

    protected function displayOrphanedOrganisations(Collection $orphanedOrganisations): void
    {
        $this->getOutput()->writeln('Orphaned organisations found:');
        foreach ($orphanedOrganisations as $organisation) {
            $this->getOutput()->writeln("- Organisation ID: {$organisation->id}");
        }
    }

    protected function deleteOrphanedOrganisations(Collection $orphanedOrganisations): void
    {
        $orphanedOrganisations->each(function (Organisation $organisation) {
            Organisation::withoutEvents(function () use ($organisation) {
                $this->organisationRepository->delete($organisation->id);
            });
            $this->getOutput()->writeln("Deleted organisation ID: {$organisation->id}");
        });
    }

}
