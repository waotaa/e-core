<?php

namespace Vng\EvaCore\Commands\Data;

use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;
use Vng\EvaCore\Models\Organisation;

class CheckAndSetOrganisationType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:check-set-organisation-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and set the organisation_type for organisations without a type.';

    public function __construct(
        protected OrganisationRepositoryInterface $organisationRepository,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting to check and set organisation_type for organisations without a type...');

        // Haal alleen organisaties zonder organisation_type op
        $organisations = $this->organisationRepository
            ->builderWithTrashed()
            ->whereNull('organisation_type')
            ->get();

        foreach ($organisations as $organisation) {
            $this->setOrganisationTypeForOrganisation($organisation);
        }

        $this->info('Finished checking and setting organisation_type!');
        return 0;
    }

    /**
     * Set the organisation_type for the given organisation.
     */
    protected function setOrganisationTypeForOrganisation(Organisation $organisation): void
    {
        // Laad soft-deleted gerelateerde modellen
        if ($organisation->localParty()->withTrashed()->exists()) {
            $organisation->setOrganisationType($organisation->localParty()->withTrashed()->first());
        } elseif ($organisation->regionalParty()->withTrashed()->exists()) {
            $organisation->setOrganisationType($organisation->regionalParty()->withTrashed()->first());
        } elseif ($organisation->nationalParty()->withTrashed()->exists()) {
            $organisation->setOrganisationType($organisation->nationalParty()->withTrashed()->first());
        } elseif ($organisation->partnership()->withTrashed()->exists()) {
            $organisation->setOrganisationType($organisation->partnership()->withTrashed()->first());
        }

        // Save the organisation if the type was set
        if ($organisation->organisation_type) {
            $organisation->save();
            $this->info("Updated organisation_type for organisation ID {$organisation->id} to {$organisation->organisation_type}");
        } else {
            $this->warn("Could not determine organisation_type for organisation ID {$organisation->id}");
        }
    }
}
