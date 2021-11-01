<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Professional;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessionalFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Professional::class;

    public function definition()
    {
        return [
            'username' => $this->faker->name,
            'email' => $this->faker->email,
            'email_verified' => $this->faker->boolean,
            'enabled' => $this->faker->boolean,
            'user_status' => 'STATUS',
        ];
    }
}
