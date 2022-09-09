<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\AddressCreateRequest;
use Vng\EvaCore\Http\Requests\AddressUpdateRequest;
use Vng\EvaCore\Models\Address;
use Vng\EvaCore\Repositories\AddressRepositoryInterface;

class AddressRepository extends BaseRepository implements AddressRepositoryInterface
{
    public string $model = Address::class;

    public function create(AddressCreateRequest $request): Address
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Address $download, AddressUpdateRequest $request): Address
    {
        return $this->saveFromRequest($download, $request);
    }

    public function saveFromRequest(Address $address, FormRequest $request): Address
    {
        $address->fill([
            'name' => $request->input('name'),
            'straatnaam' => $request->input('straatnaam'),
            'huisnummer' => $request->input('huisnummer'),
            'postbusnummer' => $request->input('postbusnummer'),
            'antwoordnummer' => $request->input('antwoordnummer'),
            'postcode' => $request->input('postcode'),
            'woonplaats' => $request->input('woonplaats'),
        ]);
        $address->save();
        return $address;
    }
}
