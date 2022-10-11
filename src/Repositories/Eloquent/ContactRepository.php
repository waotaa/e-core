<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Enums\ContactTypeEnum;
use Vng\EvaCore\Http\Requests\ContactCreateRequest;
use Vng\EvaCore\Http\Requests\ContactUpdateRequest;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Repositories\ContactRepositoryInterface;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    public string $model = Contact::class;

    public function create(ContactCreateRequest $request): Contact
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Contact $download, ContactUpdateRequest $request): Contact
    {
        return $this->saveFromRequest($download, $request);
    }

    public function saveFromRequest(Contact $contact, FormRequest $request): Contact
    {
        $contact->fill([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
        ]);
        $contact->save();
        return $contact;
    }

    public function attachInstruments(Contact $contact, array|string $instrumentIds, ?string $type = null): Contact
    {
        if (!is_null($type) && !ContactTypeEnum::search($type)) {
            throw new \Exception('invalid type given ' . $type);
        }
        $pivotValues = [
            'type' => $type
        ];
        $contact->instruments()->syncWithPivotValues((array) $instrumentIds, $pivotValues, false);
        return $contact;
    }

    public function detachInstruments(Contact $contact, array|string $instrumentIds): Contact
    {
        $contact->instruments()->detach((array) $instrumentIds);
        return $contact;
    }

    public function attachProviders(Contact $contact, array|string $providerIds, ?string $type = null): Contact
    {
        if (!is_null($type) && !ContactTypeEnum::search($type)) {
            throw new \Exception('invalid type given ' . $type);
        }
        $pivotValues = [
            'type' => $type
        ];
        $contact->providers()->syncWithPivotValues((array) $providerIds, $pivotValues, false);
        return $contact;
    }

    public function detachProviders(Contact $contact, array|string $providerIds): Contact
    {
        $contact->providers()->detach((array) $providerIds);
        return $contact;
    }
}
