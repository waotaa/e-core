<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\Provider;

class ProviderFromArrayService extends BaseFromArrayService
{
    public function handle(): Model
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
            $address = AddressFromArrayService::create($data['address']);
            $provider->address()->associate($address);
        }

        $provider->saveQuietly();

        if (isset($data['contacts'])) {
            /** @var Provider $provider */
            $provider = ContactFromArrayService::attachToContactableModel($provider, $data['contacts']);
        }

        return $provider;
    }
}