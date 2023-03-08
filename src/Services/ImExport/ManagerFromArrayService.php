<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\Manager;

class ManagerFromArrayService extends BaseFromArrayService
{
    public function handle(): ?Model
    {
        $data = $this->data;
        /** @var Manager $manager */
        $manager = Manager::query()->firstOrNew([
            'email' => $data['email'],
        ]);
        $manager->fill([
            'name' => $data['name'],
            'givenName' => $data['givenName'],
            'surName' => $data['surName'],
            'months_unupdated_limit' => $data['months_unupdated_limit'] ?? 6,
        ]);

        $manager->saveQuietly();

        $manager = $this->attachToRoles($manager);
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

    public function attachToRoles(Manager $manager)
    {
        foreach ($this->data['roles'] as $roleData) {
            $manager->assignRole($roleData['name']);
        }
        return $manager;
    }
}