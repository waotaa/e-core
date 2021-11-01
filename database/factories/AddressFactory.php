<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Address::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'straatnaam' => $this->faker->streetName,
            'huisnummer' => $this->faker->randomNumber(3),
            'postcode' => $this->faker->postcode,
            'woonplaats' => $this->faker->city,
        ];
    }
}
