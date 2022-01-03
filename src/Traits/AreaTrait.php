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

    public function getType(): string
    {
//        return get_class($this);
        return (new ReflectionClass($this))->getShortName();
    }

    public function getAreaIdentifier(): string
    {
        return $this->getType() . '-' . $this->getName();
    }

    public function getOwnAreas(): Collection
    {
        return collect([$this]);
    }

    public function getAreasLocatedIn(): Collection
    {
        // A township part is located in
        // It's own area
        $areasLocatedIn = $this->getOwnAreas();

        // It's parent township
        if ($this->getParentAreas()) {
            $this->getParentAreas()
                ->filter()
                ->map(fn (AreaInterface $area) => $area->getAreasLocatedIn())
                ->flatten()
                ->each(fn (AreaInterface $area) => $areasLocatedIn->add($area));
        }

        return AreaService::removeDuplicateAreas($areasLocatedIn);
    }

    public function getContainingAreas(): Collection
    {
        // A township is containing
        // It's own areas
        $containingAreas = $this->getOwnAreas();

        // The containing areas of it's townships
        if ($this->getChildAreas()) {
            $this->getChildAreas()
                ->filter()
                ->map(fn (AreaInterface $area) => $area->getContainingAreas())
                ->flatten()
                ->each(fn (AreaInterface $area) => $containingAreas->add($area));
        }

        return AreaService::removeDuplicateAreas($containingAreas);
    }

    public function getEncompassingAreas(): Collection
    {
        $locatedIn = $this->getAreasLocatedIn();
        $containing = $this->getContainingAreas();
        $areas = collect()->concat($locatedIn)->concat($containing);
        return AreaService::removeDuplicateAreas($areas);
    }
}

