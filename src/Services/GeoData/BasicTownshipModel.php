<?php

namespace Vng\EvaCore\Services\GeoData;
use Vng\EvaCore\Models\Township;

class BasicTownshipModel extends BasicGeoModel
{
    public Township $sourceTownship;
    public ?string $region_code;

    public static function create(): BasicTownshipModel
    {
        return new static();
    }

    public static function createFromSource(Township $township): BasicTownshipModel
    {
        return static::create()
            ->setSourceTownship($township)
            ->setCode($township->code)
            ->setName($township->name)
            ->setSlug($township->slug)
            ->setRegionCode($township->region_code);
    }

    public function getSourceTownship(): Township
    {
        return $this->sourceTownship;
    }

    public function setSourceTownship(Township $sourceTownship): BasicTownshipModel
    {
        $this->sourceTownship = $sourceTownship;
        return $this;
    }

    public function getRegionCode(): string
    {
        return $this->region_code;
    }

    public function setRegionCode(?string $region_code): BasicTownshipModel
    {
        $this->region_code = $region_code;
        return $this;
    }
}
