<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
{
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

    public function ownerless(): Factory
    {
        return $this->state(function () {
            return [
                'owner_type' => null,
                'owner_id' => null,
            ];
        });
    }

    public function forRegion(Region $region = null): Factory
    {
        return $this->for($region ?? Region::factory(), 'owner');
    }

    public function forTownship(Township $township = null): Factory
    {
        return $this->for($township ?? Township::factory(), 'owner');
    }

    public function forEnvironment(Environment $environment = null): Factory
    {
        return $this->for($environment ?? Environment::factory(), 'owner');
    }

    public function forPartnership(Partnership $partnership = null): Factory
    {
        return $this->for($partnership ?? Partnership::factory(), 'owner');
    }
}
