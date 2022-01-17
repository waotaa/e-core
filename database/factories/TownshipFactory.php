<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TownshipFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Township::class;

    public function definition(): array
    {
        $name = $this->faker->word;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => 'GM' . str_pad($this->faker->numberBetween(0, 9999), 4, 0, STR_PAD_LEFT),
            'region_code' => 'AM' . str_pad($this->faker->numberBetween(0, 9999), 2, 0, STR_PAD_LEFT),
            'description' => '',
        ];
    }

    public function forRegion(Region $region = null): Factory
    {
        return $this->for($region ?? Region::factory(), 'region');
    }
}
