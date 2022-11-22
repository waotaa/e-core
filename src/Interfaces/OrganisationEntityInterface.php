<?php

namespace Vng\EvaCore\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Models\Organisation;

interface OrganisationEntityInterface
{
    public function getOrganisationType(): string;
    public function getOrganisationClass(): string;
    public function organisation(): BelongsTo;
    public function getOrganisation(): ?Organisation;
}
