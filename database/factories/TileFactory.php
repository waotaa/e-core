<?php

namespace Database\Factories;

use Vng\EvaCore\Enums\TileEnum;
use Vng\EvaCore\Models\Tile;
use Illuminate\Database\Eloquent\Factories\Factory;

class TileFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Tile::class;

    public function definition(): array
    {
        return [
            'name' => collect(TileEnum::toArray())->random(),
        ];
    }
}
