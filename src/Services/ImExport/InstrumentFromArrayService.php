<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Enums\LocationEnum;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Models\GroupForm;
use Vng\EvaCore\Models\Implementation;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Link;
use Vng\EvaCore\Models\Location;
use Vng\EvaCore\Models\Neighbourhood;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Models\Rating;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\RegistrationCode;
use Vng\EvaCore\Models\TargetGroup;
use Vng\EvaCore\Models\Tile;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Models\Video;

class InstrumentFromArrayService extends BaseFromArrayService
{
    public function handle(): ?Model
    {
        $data = $this->data;

        /** @var Instrument $instrument */
        $instrument = Instrument::query()->firstOrNew([
            'name' => $data['name'],
            'uuid' => $data['uuid'],
        ]);

        $instrument = $instrument->fill($data);

//        // Owner
//        if (isset($data['owner'])) {
//            $owner = static::findOwner($data['owner']);
//            if (!is_null($owner)) {
//                $instrument->owner()->associate($owner);
//            }
//        }

        // Organisation
        if (!is_null($data['organisation'])) {
            $organisation = OrganisationFromArrayService::create($data['organisation']);
            $instrument->organisation()->associate($organisation);
        }

        $this->associateProviderToInstrument($instrument, $data);
        $instrument->saveQuietly();

        $this->addContactsToInstrument($instrument, $data);

        $this->addDownloadsToInstrument($instrument, $data);
        $this->addLinksToInstrument($instrument, $data);
        $this->addLocationsToInstrument($instrument, $data);
        $this->addRegistrationCodesToInstrument($instrument, $data);
        $this->addVideosToInstrument($instrument, $data);

        $this->associateImplementationByName($instrument, $data);

        $this->addRatingsToInstrument($instrument, $data);

        $this->attachClientCharacteristicByName($instrument, $data);
        $this->attachGroupFormsByName($instrument, $data);
        $this->attachTileByName($instrument, $data);
        $this->attachTargetGroupsByName($instrument, $data);
        $this->attachAvailableRegionsByName($instrument, $data);
        $this->attachAvailableTownshipsByName($instrument, $data);
        $this->attachAvailableNeighbourhoodsByName($instrument, $data);

        return $instrument;
    }

    public function associateProviderToInstrument(Instrument $instrument, $instrumentData): ?Instrument
    {
        $field = 'provider';
        if (!isset($instrumentData[$field])) {
            return null;
        }
        $providerData = $instrumentData[$field];

        $provider = ProviderFromArrayService::create($providerData);
        $instrument->provider()->associate($provider);
        return $instrument;
    }

    public function associateImplementationByName(Instrument $instrument, $instrumentData)
    {
        if (!isset($instrumentData['implementation'])) {
            return;
        }
        $implementationData = $instrumentData['implementation'];
        $implementation = Implementation::query()->firstOrNew(
            [
                'name' => $implementationData['name']
            ],
            [
                'custom' => true
            ]
        );
        $implementation->saveQuietly();

        $instrument->implementation()->associate($implementation);
        $instrument->saveQuietly();
    }

    public function addContactsToInstrument(Instrument $instrument, $instrumentData): Instrument
    {
        if (!isset($instrumentData['contacts'])) {
            return $instrument;
        }

        // apply the organisation of the instrument to all contacts
        $instrumentData = static::addOrganisationDataToChildProperty($instrumentData, 'contacts');

        /** @var Instrument $instrument */
        $instrument = ContactFromArrayService::attachToContactableModel($instrument, $instrumentData['contacts']);
        return $instrument;
    }

    public function addDownloadsToInstrument(Instrument $instrument, $instrumentData): Instrument
    {
        $field = 'downloads';
        foreach ($instrumentData[$field] as $downloadData) {
            $this->addDownload($instrument, $downloadData);
        }
        return $instrument;
    }

    public function addDownload(Instrument $instrument, $downloadData): Download
    {
        $download = new Download($downloadData);
        $download->instrument()->associate($instrument);
        $download->saveQuietly();
        return $download;
    }

    public function addLinksToInstrument(Instrument $instrument, $instrumentData): Instrument
    {
        $field = 'links';
        foreach ($instrumentData[$field] as $linkData) {
            $this->addLink($instrument, $linkData);
        }
        return $instrument;
    }

    public function addLink(Instrument $instrument, $linkData): Link
    {
        $link = new Link($linkData);
        $link->instrument()->associate($instrument);
        $link->saveQuietly();
        return $link;
    }

    public function addLocationsToInstrument(Instrument $instrument, $instrumentData)
    {
        $field = 'locations';
        foreach ($instrumentData[$field] as $locationData) {
            $locationData['organisation'] = $instrumentData['organisation'];
            $this->addLocation($instrument, $locationData);
        }
    }

    public function addLocation(Instrument $instrument, $locationData): Location
    {
        $location = new Location();

        if ($locationData['type']['name']) {
            $locationType = new LocationEnum($locationData['type']['name']);
            $location->type = $locationType;
        }

        unset($locationData['type']);
        $location = $location->fill($locationData);

        if (isset($locationData['address'])) {
            // apply the organisation of the location (instrument) to the address
            $locationData['address']['organisation'] = $locationData['organisation'];

            $address = AddressFromArrayService::create($locationData['address']);
            $location->address()->associate($address);
        }

        $location->instrument()->associate($instrument);

        $location->saveQuietly();
        return $location;
    }

    public function addRegistrationCodesToInstrument(Instrument $instrument, $instrumentData): Instrument
    {
        $field = 'registration_codes';
        foreach ($instrumentData[$field] as $registrationCodeData) {
            $this->addRegistrationCode($instrument, $registrationCodeData);
        }
        return $instrument;
    }

    public function addRegistrationCode(Instrument $instrument, $registrationCodeData): RegistrationCode
    {
        $registrationCode = new RegistrationCode($registrationCodeData);
        $registrationCode->instrument()->associate($instrument);
        $registrationCode->saveQuietly();
        return $registrationCode;
    }

    public function addVideosToInstrument(Instrument $instrument, $instrumentData): Instrument
    {
        $field = 'videos';
        foreach ($instrumentData[$field] as $videoData) {
            $this->addVideo($instrument, $videoData);
        }
        return $instrument;
    }

    public function addVideo(Instrument $instrument, $videoData): Video
    {
        $video = new Video($videoData);
        $video->instrument()->associate($instrument);
        $video->saveQuietly();
        return $video;
    }

    public function addRatingsToInstrument(Instrument $instrument, $instrumentData): Instrument
    {
        $field = 'ratings';
        foreach ($instrumentData[$field] as $ratingData) {
            $this->addRating($instrument, $ratingData);
        }
        return $instrument;
    }

    public function addRating(Instrument $instrument, $ratingData): Rating
    {
        $rating = new Rating($ratingData);

        // temporarily neccesary since email was missing from rating resource
        $rating->email = $ratingData['email'];

        $rating->instrument()->associate($instrument);
        $professional = Professional::query()->where('email', $rating->email)->first();
        if (!is_null($professional)) {
            $rating->professional()->associate($professional);
        }

        $rating->saveQuietly();
        return $rating;
    }

    public function attachClientCharacteristicByName(Instrument $instrument, $instrumentData)
    {
        if (!isset($instrumentData['client_characteristics'])) {
            return;
        }
        $clientCharacteristicsData = collect($instrumentData['client_characteristics']);
        $clientCharacteristics = ClientCharacteristic::query()->whereIn('name', $clientCharacteristicsData->pluck('name'));
        $instrument->clientCharacteristics()->syncWithoutDetaching($clientCharacteristics->pluck('id'));
    }

    public function attachGroupFormsByName(Instrument $instrument, $instrumentData)
    {
        if (!isset($instrumentData['group_forms'])) {
            return;
        }
        $groupFormsData = collect($instrumentData['group_forms']);

        // make sure all custom groups exist
        $customGroupForms = $groupFormsData->filter(fn ($gf) => !!$gf['custom']);
        $customGroupForms->each(function ($groupFormData) {
            GroupForm::query()->firstOrCreate(
                ['name' => $groupFormData['name']],
                ['custom' => true],
            );
        });

        $groupForms = GroupForm::query()->whereIn('name', $groupFormsData->pluck('name'));
        $instrument->groupForms()->syncWithoutDetaching($groupForms->pluck('id'));
    }

    public function attachTargetGroupsByName(Instrument $instrument, $instrumentData)
    {
        $field = 'target_groups';
        if (!isset($instrumentData[$field])) {
            return;
        }
        $targetGroupData = collect($instrumentData[$field]);

        // make sure all custom targetGroups exist
        $customTargetGroups = $targetGroupData->filter(fn ($tg) => !!$tg['custom']);
        $customTargetGroups->each(function ($targetGroupData) {
            TargetGroup::query()->firstOrCreate(
                ['description' => $targetGroupData['description']],
                ['custom' => true],
            );
        });

        $targetGroups = TargetGroup::query()->whereIn('description', $targetGroupData->pluck('description'));
        $instrument->targetGroups()->syncWithoutDetaching($targetGroups->pluck('id'));
    }

    public function attachTileByName(Instrument $instrument, $instrumentData)
    {
        if (!isset($instrumentData['tiles'])) {
            return;
        }
        $tilesData = collect($instrumentData['tiles']);
        $tiles = Tile::query()->whereIn('name', $tilesData->pluck('name'));
        $instrument->tiles()->syncWithoutDetaching($tiles->pluck('id'));
    }

    public function attachAvailableRegionsByName(Instrument $instrument, $instrumentData)
    {
        if (!isset($instrumentData['available_regions'])) {
            return;
        }
        $availableRegionsData = collect($instrumentData['available_regions']);
        $regions = Region::query()->whereIn('name', $availableRegionsData->pluck('name'));
        $instrument->availableRegions()->syncWithoutDetaching($regions->pluck('id'));
    }

    public function attachAvailableTownshipsByName(Instrument $instrument, $instrumentData)
    {
        if (!isset($instrumentData['available_townships'])) {
            return;
        }
        $availableTownshipsData = collect($instrumentData['available_townships']);
        $townships = Township::query()->whereIn('name', $availableTownshipsData->pluck('name'));
        $instrument->availableTownships()->syncWithoutDetaching($townships->pluck('id'));
    }

    public function attachAvailableNeighbourhoodsByName(Instrument $instrument, $instrumentData)
    {
        if (!isset($instrumentData['available_neighbourhoods'])) {
            return;
        }
        $availableNeighbourhoodsData = collect($instrumentData['available_neighbourhoods']);

        // make sure all neighbourhoods exist
        $availableNeighbourhoodsData->each(function ($neigbourhoodData) {
            /** @var Neighbourhood $neigbourhood */
            $neigbourhood = Neighbourhood::query()->firstOrNew(
                ['name' => $neigbourhoodData['name']]
            );
            $neigbourhood->township()->associate($neigbourhoodData['township']['id']);
        });

        $townships = Township::query()->whereIn('name', $availableNeighbourhoodsData->pluck('name'));
        $instrument->availableTownships()->syncWithoutDetaching($townships->pluck('id'));
    }
}