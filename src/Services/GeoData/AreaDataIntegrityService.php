<?php

namespace Vng\EvaCore\Services\GeoData;

use Vng\EvaCore\Models\Area;

class AreaDataIntegrityService
{
    public static function removeUnconnectedAreas(): void
    {
        $areas = Area::all();
        $areas->each(function(Area $area) {
            if (is_null($area->area)) {
                $area->delete();
            }
        });
    }
}
