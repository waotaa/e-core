<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
{
    use OrganisationOwnedTrait;

    /**
     * @var string
     */
    protected $model = Provider::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
