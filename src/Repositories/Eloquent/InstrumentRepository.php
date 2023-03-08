<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Vng\EvaCore\Enums\ContactTypeEnum;
use Vng\EvaCore\Http\Requests\InstrumentCreateRequest;
use Vng\EvaCore\Http\Requests\InstrumentUpdateRequest;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\GroupForm;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Neighbourhood;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\TargetGroup;
use Vng\EvaCore\Models\Tile;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Repositories\ClientCharacteristicRepositoryInterface;
use Vng\EvaCore\Repositories\ContactRepositoryInterface;
use Vng\EvaCore\Repositories\GroupFormRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Repositories\NeighbourhoodRepositoryInterface;
use Vng\EvaCore\Repositories\RegionRepositoryInterface;
use Vng\EvaCore\Repositories\TargetGroupRepositoryInterface;
use Vng\EvaCore\Repositories\TileRepositoryInterface;
use Vng\EvaCore\Repositories\TownshipRepositoryInterface;

class InstrumentRepository extends BaseRepository implements InstrumentRepositoryInterface
{
    use OwnedEntityRepository, SoftDeletableRepository;

    protected string $model = Instrument::class;

    public function create(InstrumentCreateRequest $request): Instrument
    {
        Gate::authorize('create', Instrument::class);
        return $this->saveFromRequest(new Instrument(), $request);
    }

    public function update(Instrument $instrument, InstrumentUpdateRequest $request): Instrument
    {
        Gate::authorize('update', $instrument);
        return $this->saveFromRequest($instrument, $request);
    }

    private function saveFromRequest(Instrument $instrument, FormRequest $request): Instrument
    {
        $organisationRepository = new OrganisationRepository();
        $organisation = $organisationRepository->find($request->input('organisation_id'));
        if (is_null($organisation)) {
            throw new Exception('invalid organisation provided');
        }

        $instrument = $instrument
            ->fill([
                'name' => $request->input('name'),
                'is_active' => $request->input('is_active'),
                'publish_from' => $request->input('publish_from'),
                'publish_to' => $request->input('publish_to'),
                'summary' => $request->input('summary'),
                'aim' => $request->input('aim'),
                'method' => $request->input('method'),
                'distinctive_approach' => $request->input('distinctive_approach'),
                'target_group_description' => $request->input('target_group_description'),
                'participation_conditions' => $request->input('participation_conditions'),
                'cooperation_partners' => $request->input('cooperation_partners'),
                'additional_information' => $request->input('additional_information'),
                'work_agreements' => $request->input('work_agreements'),
                'application_instructions' => $request->input('application_instructions'),
                'intensity_hours_per_week' => $request->input('intensity_hours_per_week'),
                'intensity_description' => $request->input('intensity_description'),
                'total_duration_value' => $request->input('total_duration_value'),
                'total_duration_unit' => $request->input('total_duration_unit'),
                'duration_description' => $request->input('duration_description'),
                'total_costs' => $request->input('total_costs'),
                'costs_description' => $request->input('costs_description'),
            ]
        );

        $instrument->organisation()->associate($organisation);

        if ($request->input('implementation_id')) {
            $instrument->implementation()->associate($request->input('implementation_id'));
        }

        if ($request->input('instrument_type_id')) {
            $instrument->instrumentType()->associate($request->input('instrument_type_id'));
        }

        if ($request->input('provider_id')) {
            $instrument->provider()->associate($request->input('provider_id'));
        }

        $instrument->save();

        if ($request->input('group_form_ids')) {
            $this->attachGroupForms($instrument, $request->input('group_form_ids'));
        }
        if ($request->input('available_region_ids')) {
            $this->attachAvailableRegions($instrument, $request->input('available_region_ids'));
        }
        if ($request->input('available_township_ids')) {
            $this->attachAvailableTownships($instrument, $request->input('available_township_ids'));
        }
        if ($request->input('available_neighbourhood_ids')) {
            $this->attachAvailableNeighbourhoods($instrument, $request->input('available_neighbourhood_ids'));
        }

        return $instrument;
    }

    public function attachContacts(Instrument $instrument, string|array $contactIds, ?string $type = null): Instrument
    {
        $contactIds = (array) $contactIds;
        /** @var ContactRepositoryInterface $contactRepository */
        $contactRepository = app(ContactRepositoryInterface::class);
        $contactRepository
            ->findMany($contactIds)
            ->each(
                function (Contact $contact) use ($instrument) {
                    Gate::authorize('attachContact', [$instrument, $contact]);
                }
            );

        if (!is_null($type) && !ContactTypeEnum::search($type)) {
            throw new Exception('invalid type given ' . $type);
        }
        $pivotValues = [
            'type' => $type
        ];
        $instrument->contacts()->syncWithPivotValues((array) $contactIds, $pivotValues, false);
        return $instrument;
    }

    public function detachContacts(Instrument $instrument, string|array $contactIds): Instrument
    {
        $contactIds = (array) $contactIds;
        /** @var ContactRepositoryInterface $contactRepository */
        $contactRepository = app(ContactRepositoryInterface::class);
        $contactRepository
            ->findMany($contactIds)
            ->each(
                function (Contact $contact) use ($instrument) {
                    Gate::authorize('detachContact', [$instrument, $contact]);
                }
            );

        $instrument->contacts()->detach((array) $contactIds);
        return $instrument;
    }

    public function attachClientCharacteristics(Instrument $instrument, string|array $clientCharacteristicIds): Instrument
    {
        $clientCharacteristicIds = (array) $clientCharacteristicIds;
        /** @var ClientCharacteristicRepositoryInterface $clientCharacteristicsRepository */
        $clientCharacteristicsRepository = app(ClientCharacteristicRepositoryInterface::class);
        $clientCharacteristicsRepository
            ->findMany($clientCharacteristicIds)
            ->each(
                function (ClientCharacteristic $clientCharacteristic) use ($instrument) {
                    Gate::authorize('attachClientCharacteristic', [$instrument, $clientCharacteristic]);
                }
            );

        $instrument->clientCharacteristics()->syncWithoutDetaching($clientCharacteristicIds);
        return $instrument;
    }

    public function detachClientCharacteristics(Instrument $instrument, string|array $clientCharacteristicIds): Instrument
    {
        $clientCharacteristicIds = (array) $clientCharacteristicIds;
        /** @var ClientCharacteristicRepositoryInterface $clientCharacteristicsRepository */
        $clientCharacteristicsRepository = app(ClientCharacteristicRepositoryInterface::class);
        $clientCharacteristicsRepository
            ->findMany($clientCharacteristicIds)
            ->each(
                function (ClientCharacteristic $clientCharacteristic) use ($instrument) {
                    Gate::authorize('detachClientCharacteristic', [$instrument, $clientCharacteristic]);
                }
            );

        $instrument->clientCharacteristics()->detach($clientCharacteristicIds);
        return $instrument;
    }

    public function attachGroupForms(Instrument $instrument, string|array $groupFormIds): Instrument
    {
        $groupFormIds = (array) $groupFormIds;
        /** @var GroupFormRepositoryInterface $groupFormRepository */
        $groupFormRepository = app(GroupFormRepositoryInterface::class);
        $groupFormRepository
            ->findMany($groupFormIds)
            ->each(
                function (GroupForm $groupForm) use ($instrument) {
                    Gate::authorize('attachGroupForm', [$instrument, $groupForm]);
                }
            );

        $instrument->groupForms()->syncWithoutDetaching($groupFormIds);
        return $instrument;
    }

    public function detachGroupForms(Instrument $instrument, string|array $groupFormIds): Instrument
    {
        $groupFormIds = (array) $groupFormIds;
        /** @var GroupFormRepositoryInterface $groupFormRepository */
        $groupFormRepository = app(GroupFormRepositoryInterface::class);
        $groupFormRepository
            ->findMany($groupFormIds)
            ->each(
                function (GroupForm $groupForm) use ($instrument) {
                    Gate::authorize('detachGroupForm', [$instrument, $groupForm]);
                }
            );

        $instrument->groupForms()->detach($groupFormIds);
        return $instrument;
    }

    public function attachTargetGroups(Instrument $instrument, string|array $targetGroupIds): Instrument
    {
        $targetGroupIds = (array) $targetGroupIds;
        /** @var TargetGroupRepositoryInterface $targetGroupRepository */
        $targetGroupRepository = app(TargetGroupRepositoryInterface::class);
        $targetGroupRepository
            ->findMany($targetGroupIds)
            ->each(
                function (TargetGroup $targetGroup) use ($instrument) {
                    Gate::authorize('attachTargetGroup', [$instrument, $targetGroup]);
                }
            );

        $instrument->targetGroups()->syncWithoutDetaching($targetGroupIds);
        return $instrument;
    }

    public function detachTargetGroups(Instrument $instrument, string|array $targetGroupIds): Instrument
    {
        $targetGroupIds = (array) $targetGroupIds;
        /** @var TargetGroupRepositoryInterface $targetGroupRepository */
        $targetGroupRepository = app(TargetGroupRepositoryInterface::class);
        $targetGroupRepository
            ->findMany($targetGroupIds)
            ->each(
                function (TargetGroup $targetGroup) use ($instrument) {
                    Gate::authorize('detachTargetGroup', [$instrument, $targetGroup]);
                }
            );

        $instrument->targetGroups()->detach($targetGroupIds);
        return $instrument;
    }

    public function attachTiles(Instrument $instrument, string|array $tileIds): Instrument
    {
        $tileIds = (array) $tileIds;
        /** @var TileRepositoryInterface $tileRepository */
        $tileRepository = app(TileRepositoryInterface::class);
        $tileRepository
            ->findMany($tileIds)
            ->each(
                function (Tile $tile) use ($instrument) {
                    Gate::authorize('attachTile', [$instrument, $tile]);
                }
            );

        $instrument->tiles()->syncWithoutDetaching($tileIds);
        return $instrument;
    }

    public function detachTiles(Instrument $instrument, string|array $tileIds): Instrument
    {
        $tileIds = (array) $tileIds;
        /** @var TileRepositoryInterface $tileRepository */
        $tileRepository = app(TileRepositoryInterface::class);
        $tileRepository
            ->findMany($tileIds)
            ->each(
                function (Tile $tile) use ($instrument) {
                    Gate::authorize('detachTile', [$instrument, $tile]);
                }
            );

        $instrument->tiles()->detach($tileIds);
        return $instrument;
    }

    public function attachAvailableRegions(Instrument $instrument, string|array $regionIds): Instrument
    {
        $regionIds = (array) $regionIds;
        /** @var RegionRepositoryInterface $regionRepository */
        $regionRepository = app(RegionRepositoryInterface::class);
        $regionRepository
            ->findMany($regionIds)
            ->each(
                function (Region $region) use ($instrument) {
                    Gate::authorize('attachAvailableRegion', [$instrument, $region]);
                }
            );

        $instrument->availableRegions()->syncWithoutDetaching($regionIds);
        return $instrument;
    }

    public function detachAvailableRegions(Instrument $instrument, string|array $regionIds): Instrument
    {
        $regionIds = (array) $regionIds;
        /** @var RegionRepositoryInterface $regionRepository */
        $regionRepository = app(RegionRepositoryInterface::class);
        $regionRepository
            ->findMany($regionIds)
            ->each(
                function (Region $region) use ($instrument) {
                    Gate::authorize('detachAvailableRegion', [$instrument, $region]);
                }
            );

        $instrument->availableRegions()->detach($regionIds);
        return $instrument;
    }

    public function attachAvailableTownships(Instrument $instrument, string|array $townshipIds): Instrument
    {
        $townshipIds = (array) $townshipIds;
        /** @var TownshipRepositoryInterface $townshipRepository */
        $townshipRepository = app(TownshipRepositoryInterface::class);
        $townshipRepository
            ->findMany($townshipIds)
            ->each(
                function (Township $township) use ($instrument) {
                    Gate::authorize('attachAvailableTownship', [$instrument, $township]);
                }
            );

        $instrument->availableTownships()->syncWithoutDetaching((array) $townshipIds);
        return $instrument;
    }

    public function detachAvailableTownships(Instrument $instrument, string|array $townshipIds): Instrument
    {
        $townshipIds = (array) $townshipIds;
        /** @var TownshipRepositoryInterface $townshipRepository */
        $townshipRepository = app(TownshipRepositoryInterface::class);
        $townshipRepository
            ->findMany($townshipIds)
            ->each(
                function (Township $township) use ($instrument) {
                    Gate::authorize('detachAvailableTownship', [$instrument, $township]);
                }
            );

        $instrument->availableTownships()->detach((array) $townshipIds);
        return $instrument;
    }

    public function attachAvailableNeighbourhoods(Instrument $instrument, string|array $neighbourhoodIds): Instrument
    {
        $neighbourhoodIds = (array) $neighbourhoodIds;
        /** @var NeighbourhoodRepositoryInterface $neighbourhoodRepository */
        $neighbourhoodRepository = app(NeighbourhoodRepositoryInterface::class);
        $neighbourhoodRepository
            ->findMany($neighbourhoodIds)
            ->each(
                function (Neighbourhood $neighbourhood) use ($instrument) {
                    Gate::authorize('attachAvailableNeighbourhood', [$instrument, $neighbourhood]);
                }
            );

        $instrument->availableNeighbourhoods()->syncWithoutDetaching((array) $neighbourhoodIds);
        return $instrument;
    }

    public function detachAvailableNeighbourhoods(Instrument $instrument, string|array $neighbourhoodIds): Instrument
    {
        $neighbourhoodIds = (array) $neighbourhoodIds;
        /** @var NeighbourhoodRepositoryInterface $neighbourhoodRepository */
        $neighbourhoodRepository = app(NeighbourhoodRepositoryInterface::class);
        $neighbourhoodRepository
            ->findMany($neighbourhoodIds)
            ->each(
                function (Neighbourhood $neighbourhood) use ($instrument) {
                    Gate::authorize('detachAvailableNeighbourhood', [$instrument, $neighbourhood]);
                }
            );

        $instrument->availableNeighbourhoods()->detach((array) $neighbourhoodIds);
        return $instrument;
    }
}
