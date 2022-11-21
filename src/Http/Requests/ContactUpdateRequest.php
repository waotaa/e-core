<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ContactValidation;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Repositories\ContactRepositoryInterface;

class ContactUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getContact());
    }

    public function rules(): array
    {
        $contact = $this->getContact();
        if (!$contact instanceof Contact) {
            throw new \Exception('Cannot derive contact from route');
        }
        return ContactValidation::make($this)->getUpdateRules($contact);
    }

    protected function getContact()
    {
        /** @var ContactRepositoryInterface $contactRepository */
        $contactRepository = App::make(ContactRepositoryInterface::class);
        return $contactRepository->find($this->route('contactId'));
    }
}
