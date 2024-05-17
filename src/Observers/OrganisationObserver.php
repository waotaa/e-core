<?php

namespace Vng\EvaCore\Observers;

use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;

class OrganisationObserver
{
    public function __construct(
        protected OrganisationRepositoryInterface $organisationRepository
    )
    {}

    public function forceDeleting(Organisation $organisation)
    {
        Log::debug('force deleting organisation');
        Log::debug('routing hard delete to organisation entity');
        if ($organisation->localParty) {
            $organisation->localParty->forceDelete();
            return false;
        }

        if ($organisation->regionalParty) {
            $organisation->regionalParty->forceDelete();
            return false;
        }

        if ($organisation->nationalParty) {
            $organisation->nationalParty->forceDelete();
            return false;
        }

        if ($organisation->partnership) {
            $organisation->partnership->forceDelete();
            return false;
        }

        return null; // Allow the Organisation to be deleted if no related entities are found
    }

    protected function trashed(Organisation $organisation)
    {
        Log::debug('soft deleted organisation');

        if ($organisation->localParty && !$organisation->localParty->trashed()) {
            $organisation->localParty->delete();
        }

        if ($organisation->regionalParty && !$organisation->regionalParty->trashed()) {
            $organisation->regionalParty->delete();
        }

        if ($organisation->nationalParty && !$organisation->nationalParty->trashed()) {
            $organisation->nationalParty->delete();
        }

        if ($organisation->partnership && !$organisation->partnership->trashed()) {
            $organisation->partnership->delete();
        }
    }
}
