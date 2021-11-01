<?php

namespace Vng\EvaCore\Services\GeoComparison;

use Vng\EvaCore\Services\GeoData\BasicRegionModel;

class RegionComparison extends GeoComparison
{
    const COMPARABLE_ATTRIBUTES = [
        'name',
        'slug',
        'color'
    ];

    public function __construct(BasicRegionModel $modelA = null, BasicRegionModel $modelB = null, array $attributes = null)
    {
        parent::__construct($modelA, $modelB, $attributes);
    }
}
