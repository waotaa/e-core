<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
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

    public function featureTownship(Township $township = null): Factory
    {
        return $this->state([])->for(
            $township ?? Township::factory(), 'featuredAssociation'
        );
    }

    public function featureRegion(Region $region = null): Factory
    {
        return $this->state([])->for(
            $region ?? Region::factory(), 'featuredAssociation'
        );
    }

    public function featurePartnership(Partnership $partnership = null): Factory
    {
        return $this->state([])->for(
            $partnership ?? Partnership::factory(), 'featuredAssociation'
        );
    }
}
