<?php

namespace Vng\EvaCore\Commands\Format;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\RegionalParty;
use Vng\EvaCore\Repositories\Eloquent\OrganisationRepository;

class EnsureOrganisations extends Command
{
    protected $signature = 'format:ensure-organisations';
    protected $description = 'Ensures every organisationable entity has an organisations';

    protected OrganisationRepository $organisationRepository;

    public function __construct(OrganisationRepository $organisationRepository)
    {
        $this->organisationRepository = $organisationRepository;
        parent::__construct();
    }

    public function handle(): int
    {
        $this->getOutput()->writeln('starting ensuring organisations...');

        $this->ensureLocalParties();
        $this->output->writeln('local parties have organisation');

        $this->ensureRegionalParties();
        $this->output->writeln('regional parties have organisation');

        $this->ensureNationalParties();
        $this->output->writeln('national parties have organisation');

        $this->ensurePartnerships();
        $this->output->writeln('partnerships have organisation');

        $this->removeObsoleteOrganisations();
        $this->output->writeln('(soft) deleted obsolete organisation');

        $this->getOutput()->writeln('ensuring organisations finished!');
        return 0;
    }

    public function ensureLocalParties()
    {
        $withoutOrg = LocalParty::query()->whereDoesntHave('organisation')->get();
        $withoutOrg->each(function (LocalParty $localParty) {
            $this->checkForOrganisation($localParty);
        });
    }

    public function ensureRegionalParties()
    {
        $withoutOrg = RegionalParty::query()->whereDoesntHave('organisation')->get();
        $withoutOrg->each(function (RegionalParty $regionalParty) {
            $this->checkForOrganisation($regionalParty);
        });
    }

    public function ensureNationalParties()
    {
        $withoutOrg = NationalParty::query()->whereDoesntHave('organisation')->get();
        $withoutOrg->each(function (NationalParty $nationalParty) {
            $this->checkForOrganisation($nationalParty);
        });
    }

    public function ensurePartnerships()
    {
        $withoutOrg = Partnership::query()->whereDoesntHave('organisation')->get();
        $withoutOrg->each(function (Partnership $partnership) {
            $this->checkForOrganisation($partnership);
        });
    }

    private function checkForOrganisation(Model $model)
    {
        $model->fresh();
        if (!$model->organisation) {
            $organisation = $this->organisationRepository->new();
            $organisation->save();
            $model->organisation()->associate($organisation);
            $model->save();
        }
    }

    private function removeObsoleteOrganisations()
    {
        $ids = $this->getActiveOrganisationIds();
        $query = $this->organisationRepository->builder();
        $query->whereNotIn('id', $ids)->delete();
    }

    private function getActiveOrganisationIds()
    {
        $ids = array_merge(
            $this->getLocalPartyOrganisationIds(),
            $this->getRegionPartyOrganisationIds(),
            $this->getNationalPartyOrganisationIds(),
            $this->getPartnershipOrganisationIds()
        );
        sort($ids);
        return $ids;
    }

    private function getLocalPartyOrganisationIds()
    {
        $localParties = LocalParty::all();
        return $localParties->pluck('organisation.id')->toArray();
    }
    private function getRegionPartyOrganisationIds()
    {
        $localParties = RegionalParty::all();
        return $localParties->pluck('organisation.id')->toArray();
    }
    private function getNationalPartyOrganisationIds()
    {
        $localParties = NationalParty::all();
        return $localParties->pluck('organisation.id')->toArray();
    }
    private function getPartnershipOrganisationIds()
    {
        $localParties = Partnership::all();
        return $localParties->pluck('organisation.id')->toArray();
    }
}
