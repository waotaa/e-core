<?php

namespace Database\Factories;

use Vng\EvaCore\Enums\ContactTypeEnum;
use Vng\EvaCore\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\RegionalParty;

class ContactFactory extends Factory
{
    use OrganisationOwnedTrait;

    /**
     * @var string
     */
    protected $model = Contact::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'type' => collect(ContactTypeEnum::toArray())->random()
        ];
    }
}
