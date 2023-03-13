<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Vng\EvaCore\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class RegionFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Region::class;

    public function definition(): array
    {
        $name = $this->faker->word;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => 'AM' . str_pad($this->faker->numberBetween(0, 9999), 2, 0, STR_PAD_LEFT),
            'color' => $this->pickRandomColor(),
            'description' => '',
            'cooperation_partners' => '',
        ];
    }

    private function pickRandomColor(): string
    {
        $colors = new Collection([
            'cc0033',
            'd11a47',
            'd6335c',
            'db4d70',
            'e06685',
            'e68099',
            'eb99ad',
            'f0b3c2',
            'f5ccd6',
            'fae6eb'
        ]);
        return '#' . $colors->random();
    }
}
