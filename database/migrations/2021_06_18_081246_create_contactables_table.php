<?php

use App\Models\Contact;
use App\Models\Environment;
use App\Models\Instrument;
use App\Models\Provider;
use App\Models\Region;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('contactables', function (Blueprint $table) {
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->morphs('contactable');
        });

        $this->reallocateContactsFromOneToManyToManyToManyRelation();
    }

    public function reallocateContactsFromOneToManyToManyToManyRelation(): void
    {
        $contacts = Contact::all();
        $contacts->each(function(Contact $contact) {
            if ($contact->getAttribute('contactable_id') !== 0){
                /** @var Environment|Instrument|Provider|Region|null $contactable */
                $contactable = $contact->contactable;
                if ($contactable instanceof Instrument || $contactable instanceof Provider || $contactable instanceof Region) {
                    $contactable->contacts()->attach($contact->id);
                    return;
                }
                if ($contactable instanceof Environment) {
                    $contactable->contact()->associate($contact->id);
                    $contactable->save();
                    return;
                }
                if (is_null($contactable)) {
                    // linked contactable is (soft) deleted, we can delete the contact
                    $contact->delete();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactables');
    }
}
