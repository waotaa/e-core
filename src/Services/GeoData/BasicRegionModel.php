<?php

namespace Vng\EvaCore\Services\GeoData;

use Vng\EvaCore\Models\Region;

class BasicRegionModel extends BasicGeoModel
{
    public Region $sourceRegion;
    public ?string $color;

    public static function create(): BasicRegionModel
    {
        return new static();
    }

    public static function createFromSource(Region $region): BasicRegionModel
    {
        return static::create()
            ->setSourceRegion($region)
            ->setCode($region->code)
            ->setName($region->name)
            ->setSlug($region->slug)
            ->setColor($region->color);
    }

    public function getSourceRegion(): Region
    {
        return $this->sourceRegion;
    }

    public function setSourceRegion(Region $sourceRegion): BasicRegionModel
    {
        $this->sourceRegion = $sourceRegion;
        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(?string $color): BasicRegionModel
    {
        $this->color = $color;
        return $this;
    }
}
