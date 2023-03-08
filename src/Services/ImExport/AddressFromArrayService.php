<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\Address;

class AddressFromArrayService extends BaseFromArrayService
{
    public function handle(): ?Model
    {
        $data = $this->data;

        $organisation = null;
        if (!is_null($data['organisation'])) {
            $organisation = OrganisationFromArrayService::create($data['organisation']);
        }

        $q = Address::query();
        if (!is_null($organisation)) {
            $q = $q->where('organisation_id', $organisation->id);
        }

        $address = $q->firstOrNew([
            'huisnummer' => $data['huisnummer'],
            'postcode' => $data['postcode'],
        ],[
            'name' => $data['name'],
            'straatnaam' => $data['straatnaam'],
            'postbusnummer' => $data['postbusnummer'],
            'antwoordnummer' => $data['antwoordnummer'],
            'woonplaats' => $data['woonplaats'],
        ]);

        if (!is_null($organisation)) {
            $address->organisation()->associate($organisation);
        }

        $address->saveQuietly();
        return $address;
    }
}