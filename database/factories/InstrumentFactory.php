<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\NationalParty;
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
            'summary' => $this->faker->text,
            'method' => $this->faker->text,
        ];
    }

    public function nationallyAvailable(): Factory
    {
        return $this->state(function () {
            return [
                'owner_type' => null,
                'owner_id' => null,
            ];
        });
    }

    public function forNationalParty(NationalParty $nationalParty = null): Factory
    {
        return $this->for($nationalParty ?? NationalParty::factory(), 'owner');
    }

    public function forRegion(Region $region = null): Factory
    {
        return $this->for($region ?? Region::factory()->create(), 'owner');
    }

    public function forTownship(Township $township = null): Factory
    {
        return $this->for($township ?? Township::factory()->forRegion()->create(), 'owner');
    }

    public function forPartnership(Partnership $partnership = null): Factory
    {
        return $this->for(
            $partnership ?? Partnership::factory(), 'owner'
        );
    }
}
