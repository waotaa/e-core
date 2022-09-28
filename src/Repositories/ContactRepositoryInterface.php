<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ContactCreateRequest;
use Vng\EvaCore\Http\Requests\ContactUpdateRequest;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Instrument;

interface ContactRepositoryInterface extends BaseRepositoryInterface
{
    public function create(ContactCreateRequest $request): Contact;
    public function update(Contact $download, ContactUpdateRequest $request): Contact;

    public function attachInstruments(Contact $contact, string|array $instrumentIds, ?string $type = null): Contact;
    public function detachInstruments(Contact $contact, string|array $instrumentIds): Contact;

    public function attachProviders(Contact $contact, string|array $providerIds, ?string $type = null): Contact;
    public function detachProviders(Contact $contact, string|array $providerIds): Contact;
}
