<?php

namespace Vng\EvaCore\Services\ImExport;

use Vng\EvaCore\Models\Contact;
use Illuminate\Database\Eloquent\Model;

class ContactFromArrayService extends BaseFromArrayService
{
    public function handle(): Model
    {
        $data = $this->data;
        $contact = Contact::query()->firstOrNew([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email']
        ]);
        $contact->saveQuietly();
        return $contact;
    }

    public static function attachToContactableModel(Model $model, $data)
    {
        $contacts = collect();
        foreach ($data as $contactData) {
            $contact = static::create($contactData);
            $contacts->add([
                'data' => $contactData,
                'instance' => $contact
            ]);
        }

        $syncData = $contacts
            ->keyBy('instance.id')
            ->map(fn ($c) => ['type' => $c['data']['type']['name']]); // pivot data

        Model::withoutEvents(fn () => $model->contacts()->sync($syncData, false));
        return $model;
    }
}