<?php

namespace Database\Factories;

use Vng\EvaCore\Enums\LocationEnum;
use Vng\EvaCore\Models\Area;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Instrument::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'is_active' => true,
            'is_nationally_available' => $this->faker->boolean,
            'location' => collect(LocationEnum::keys())->random(),
        ];
    }

    public function nationallyAvailable(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_nationally_available' => true,
                'owner_type' => null,
                'owner_id' => null,
            ];
        });
    }

    public function forRegion(Region $region = null): Factory
    {
        if (is_null($region)) {
            $area = Area::factory()->for(Region::factory(), 'area')->create();
            $region = $area->area;
        }

        return $this->state(function (array $attributes) {
            return [
                'is_nationally_available' => false,
            ];
        })->for($region, 'owner');
    }

    public function forTownship(Township $township = null): Factory
    {
        if (is_null($township)) {
            $area = Area::factory()->for(Township::factory()->forRegion(), 'area')->create();
            $township = $area->area;
        }

        return $this->state(function (array $attributes) {
            return [
                'is_nationally_available' => false,
            ];
        })->for($township, 'owner');
    }

    public function forPartnership(Partnership $partnership = null): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_nationally_available' => false,
            ];
        })->for(
            $partnership ?? Partnership::factory(), 'owner'
        );
    }
}
