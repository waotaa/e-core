<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Support\Collection;
use ReflectionClass;
use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Services\AreaService;

trait AreaTrait
{
    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getType(): string
    {
//        return get_class($this);
        return (new ReflectionClass($this))->getShortName();
    }

    public function getAreaIdentifier(): string
    {
        return $this->getType() . '-' . $this->getName();
    }

    /**
     * The Areas this object resides in
     * @return Collection
     */
    public function getOwnAreas(): Collection
    {
        return collect([$this]);
    }

    /**
     * Returns the own areas and the parent areas of those areas
     * @return Collection
     */
    public function getAreasLocatedIn(): Collection
    {
        $areasLocatedIn = $this->getOwnAreas();

        if ($this->getParentAreas()) {
            $this->getParentAreas()
                ->filter()
                ->map(fn (AreaInterface $area) => $area->getAreasLocatedIn())
                ->flatten()
                ->each(fn (AreaInterface $area) => $areasLocatedIn->add($area));
        }

        return AreaService::removeDuplicateAreas($areasLocatedIn);
    }

    /**
     * Returns the own areas and the areas within those areas
     * @return Collection
     */
    public function getContainingAreas(): Collection
    {
        $containingAreas = $this->getOwnAreas();

        if ($this->getChildAreas()) {
            $this->getChildAreas()
                ->filter()
                ->map(fn (AreaInterface $area) => $area->getContainingAreas())
                ->flatten()
                ->each(fn (AreaInterface $area) => $containingAreas->add($area));
        }

        return AreaService::removeDuplicateAreas($containingAreas);
    }

    /**
     * Returns both the parent areas as the areas within the own areas
     * @return Collection
     */
    public function getEncompassingAreas(): Collection
    {
        $locatedIn = $this->getAreasLocatedIn();
        $containing = $this->getContainingAreas();
        $areas = collect()->concat($locatedIn)->concat($containing);
        return AreaService::removeDuplicateAreas($areas);
    }
}

