<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ContactCreateRequest;
use Vng\EvaCore\Http\Requests\ContactUpdateRequest;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Instrument;

interface ContactRepositoryInterface extends OwnedEntityRepositoryInterface
{
    public function create(ContactCreateRequest $request): Contact;
    public function update(Contact $download, ContactUpdateRequest $request): Contact;

    public function attachInstruments(Contact $contact, string|array $instrumentIds, ?string $type = null, ?string $label = null): Contact;
    public function detachInstruments(Contact $contact, string|array $instrumentIds): Contact;

    public function attachProviders(Contact $contact, string|array $providerIds, ?string $type = null, ?string $label = null): Contact;
    public function detachProviders(Contact $contact, string|array $providerIds): Contact;
}
