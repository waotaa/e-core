<?php

namespace Database\Factories;

use Vng\EvaCore\Enums\ContactTypeEnum;
use Vng\EvaCore\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
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
