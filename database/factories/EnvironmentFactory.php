<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Environment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnvironmentFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Environment::class;

    public function definition()
    {
        return [
            'name' => 'Omgeving',
        ];
    }
}
