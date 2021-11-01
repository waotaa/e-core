<?php

namespace Vng\EvaCore\Services\GeoComparison;

use Vng\EvaCore\Services\GeoData\BasicGeoModel;
use Exception;
use Illuminate\Support\Collection;

abstract class GeoDataComparisonService
{
    protected Collection $collectionA;
    protected Collection $collectionB;

    public function __construct(Collection $collectionA, Collection $collectionB)
    {
        $collectionA = $this->filterNonGeoItems($collectionA);
        if ($collectionA->isEmpty()) {
            throw new Exception('Invalid current collection provided - no BasicGeoModel items found');
        }
        $this->collectionA = $collectionA;

        $collectionB = $this->filterNonGeoItems($collectionB);
        if ($collectionB->isEmpty()) {
            throw new Exception('Invalid comparison collection provided - no BasicGeoModel items found');
        }
        $this->collectionB = $collectionB;
    }

    private function filterNonGeoItems(Collection $collection): Collection
    {
        return $collection->filter(fn ($item) => $item instanceof BasicGeoModel);
    }

    public function getItemCountCollectionA(): int
    {
        return $this->getUniqueItems($this->collectionA)->count();
    }

    public function getItemCountCollectionB(): int
    {
        return $this->getUniqueItems($this->collectionB)->count();
    }

    public function getCountDifference(): int
    {
        return $this->getItemCountCollectionB() - $this->getItemCountCollectionA();
    }

    public function findItemsNotInCollectionA(): Collection
    {
        return $this->findItemsInAMissingInB($this->collectionB, $this->collectionA);
    }

    public function findItemsNotInCollectionB(): Collection
    {
        return $this->findItemsInAMissingInB($this->collectionA, $this->collectionB);
    }

    protected static function getItemByCode(Collection $collection, string $code)
    {
        return $collection->firstWhere('code', $code);
    }

    private function getUniqueItems(Collection $collection): Collection
    {
        return $collection->unique('code')->values();
    }

    private function findItemsInAMissingInB(Collection $collectionA, Collection $collectionB): Collection
    {
        $collectionCodes = $collectionB->pluck('code');
        return $collectionA->filter(fn(BasicGeoModel $item) => !$collectionCodes->contains($item->getCode()));
    }
}
