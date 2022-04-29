<?php

namespace Vng\EvaCore\Interfaces;

use Vng\EvaCore\Models\Instrument;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

interface IsMemberInterface
{
    public function associateables(): HasMany;

    public function getAssociations(): ?Collection;

    public function nationalParties(): MorphToMany;

    public function regions(): MorphToMany;

    public function partnerships(): MorphToMany;

    public function townships(): MorphToMany;

    public function hasAnyAssociation(): bool;

    public function usersShareAssociation(EvaUserInterface $user): bool;

    public function managesInstrument(Instrument $instrument): bool;

//    public function association(): MorphTo;
//
//    public function isAssociated(): bool;
//
//    public function getAssociationType(): ?string;
//
//    public function isMemberOfRegion(): bool;
//
//    public function isMemberOfTownship(): bool;
//
//    public function isMemberOfPartnership(): bool;

}
