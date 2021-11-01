<?php


namespace Vng\EvaCore\Traits;

use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Contactables;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasContacts
{
    public function contacts(): MorphToMany
    {
        return $this->morphToMany(Contact::class, 'contactable')
            ->using(Contactables::class)->withPivot('type');
    }
}
