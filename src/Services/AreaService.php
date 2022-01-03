<?php


namespace Vng\EvaCore\Services;

use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Models\Region;

class AreaService
{
    public static function getEncompassingAreasForCollection(Collection $areas)
    {
        $areas = $areas->map(function(AreaInterface $area) {
            return $area->getEncompassingAreas();
        })
            ->filter()      // remove possible null values
            ->flatten();

        return self::removeDuplicateAreas($areas);
    }

    public static function getAreasLocatedInForCollection(Collection $areas)
    {
        $areas = $areas->map(function(AreaInterface $area) {
            return $area->getAreasLocatedIn();
        })
            ->filter()      // remove possible null values
            ->flatten();

        return self::removeDuplicateAreas($areas);
    }

    public static function getContainingAreasForCollection(Collection $areas)
    {
        $areas =  $areas->map(function(AreaInterface $area) {
            return $area->getContainingAreas();
        })
            // remove possible null values
            ->filter()
            ->flatten();

        return self::removeDuplicateAreas($areas);
    }

    public static function mergeAreaCollections(Collection $areasA, Collection $areasB): Collection
    {
        $areasA->concat($areasB);
        return self::removeDuplicateAreas($areasA);
    }

    public static function removeDuplicateAreas(Collection $areas): Collection
    {
        return $areas
            // remove possible null values
            ->filter()
            // unique preserves index keys
            ->unique(fn (AreaInterface $area) => $area->getAreaIdentifier())
            // return values so keys get lost / json_encode returns an array, not an object
            ->values();
    }

    public static function removeAreaFromCollection(Collection $collection, AreaInterface $area): Collection
    {
        $key = $collection->search(
            fn (AreaInterface $item) => $item->getAreaIdentifier() === $area->getAreaIdentifier()
        );
        if ($key !== false) {
            $collection->forget($key);
        }
        return $collection->values();
    }

    public static function getNationalAreas(): Collection
    {
        return Region::all();
    }
}

