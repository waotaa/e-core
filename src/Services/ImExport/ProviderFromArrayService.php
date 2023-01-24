<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\Provider;

class ProviderFromArrayService extends BaseFromArrayService
{
    public function handle(): ?Model
    {
        $data = $this->data;

        /** @var Provider $provider */
        $provider = Provider::query()->firstOrNew([
            'name' => $data['name'],
            'uuid' => $data['uuid']
        ]);

        $provider = $provider->fill($data);

//        // Owner
//        if (isset($data['owner'])) {
//            $owner = static::findOwner($data['owner']);
//            if (!is_null($owner)) {
//                $provider->owner()->associate($owner);
//            }
//        }

        if (!is_null($data['organisation'])) {
            $organisation = OrganisationFromArrayService::create($data['organisation']);
            $provider->organisation()->associate($organisation);
        }

        if (isset($data['address'])) {
            // apply the organisation of the provider to the address
            $data['address']['organisation'] = $data['organisation'];

            $address = AddressFromArrayService::create($data['address']);
            $provider->address()->associate($address);
        }

        $provider->saveQuietly();

        if (isset($data['contacts'])) {
            // apply the organisation of the instrument to all contacts
            $data = static::addOrganisationDataToChildProperty($data, 'contacts');

            /** @var Provider $provider */
            $provider = ContactFromArrayService::attachToContactableModel($provider, $data['contacts']);
        }

        return $provider;
    }
}