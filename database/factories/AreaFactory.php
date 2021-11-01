<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Area;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Area::class;

    public function definition()
    {
        $possibleArea = [
            Region::class,
            Township::class
        ];
        $areaClass = $this->faker->randomElement($possibleArea);

        return [
            'area_id' => Factory::factoryForModel($areaClass),
            'area_type' => $areaClass,
        ];
    }
}
