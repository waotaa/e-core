<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\Manager;

class ManagerFromArrayService extends BaseFromArrayService
{
    public function handle(): Model
    {
        $data = $this->data;
        /** @var Manager $manager */
        $manager = Manager::query()->firstOrNew([
            'email' => $data['email'],
        ]);
        $manager->fill([
            'givenName' => $data['givenName'],
            'surName' => $data['surName'],
            'months_unupdated_limit' => $data['months_unupdated_limit'] ?? 6,
        ]);

        $manager->saveQuietly();

        return $this->attachToOrganisations($manager);
    }

    public function attachToOrganisations(Manager $manager)
    {
        $organisationIds = collect();
        foreach ($this->data['organisations'] as $organisationData) {
            $organisation = OrganisationFromArrayService::create($organisationData);
            $organisationIds->add($organisation->id);
        }

        $manager->organisations()->syncWithoutDetaching($organisationIds);
        return $manager;
    }
}