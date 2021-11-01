<?php

namespace Vng\EvaCore\Services\GeoComparison;

use Vng\EvaCore\Services\GeoData\BasicTownshipModel;

class TownshipComparison extends GeoComparison
{
    const COMPARABLE_ATTRIBUTES = [
        'name',
        'slug',
        'region_code'
    ];

    public function __construct(BasicTownshipModel $modelA = null, BasicTownshipModel $modelB = null, array $attributes = null)
    {
        parent::__construct($modelA, $modelB, $attributes);
    }
}
