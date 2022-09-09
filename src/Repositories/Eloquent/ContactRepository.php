<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
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

    public function saveFromRequest(Contact $address, FormRequest $request): Contact
    {
        $address->fill([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'type' => $request->input('type'),
        ]);
        $address->save();
        return $address;
    }
}
