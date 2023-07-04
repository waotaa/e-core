<?php


namespace Vng\EvaCore\Interfaces;

use Illuminate\Support\Collection;

interface AreaInterface
{
    public function getName(): string;

    public function getSlug(): string;

    public function getType(): string;

    public function getAreaIdentifier(): string;

    public function getOwnAreas(): Collection;

    public function getParentAreas(): ?Collection;

    public function getChildAreas(): ?Collection;

    public function getAreasLocatedIn(): Collection;

    public function getContainingAreas(): Collection;

    public function getEncompassingAreas(): Collection;
}
