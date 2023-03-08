<?php

namespace Vng\EvaCore\Services\ImExport;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\RegionalParty;

class OrganisationFromArrayService extends BaseFromArrayService
{
    public function handle(): ?Model
    {
        $data = $this->data;
        switch ($data['type']) {
            case 'LocalParty':
                /** @var LocalParty $localParty */
                $localParty = LocalPartyFromArrayService::create($data['localParty']);
                return $localParty->organisation;
            case 'RegionalParty':
                /** @var RegionalParty $regionalParty */
                $regionalParty = RegionalPartyFromArrayService::create($data['regionalParty']);
                return $regionalParty->organisation;
            case 'NationalParty':
                /** @var NationalParty $nationalParty */
                $nationalParty = NationalPartyFromArrayService::create($data['nationalParty']);
                return $nationalParty->organisation;
            case 'Partnership':
                /** @var Partnership $partnership */
                $partnership = PartnershipFromArrayService::create($data['partnership']);
                return $partnership->organisation;
            default:
                throw new Exception('organisation ' . $data['type'] . ' : ' . $data['name'] . ' not found');
        }
    }
}