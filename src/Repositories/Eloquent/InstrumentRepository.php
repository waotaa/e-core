<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\InstrumentCreateRequest;
use Vng\EvaCore\Http\Requests\InstrumentUpdateRequest;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class InstrumentRepository extends BaseRepository implements InstrumentRepositoryInterface
{
    use OwnedEntityRepository, SoftDeletableRepository;

    protected string $model = Instrument::class;

    public function create(InstrumentCreateRequest $request): Instrument
    {
        return $this->saveFromRequest(new Instrument(), $request);
    }

    public function update(Instrument $instrument, InstrumentUpdateRequest $request): Instrument
    {
        return $this->saveFromRequest($instrument, $request);
    }

    private function saveFromRequest(Instrument $instrument, FormRequest $request): Instrument
    {
        $organisationRepository = new OrganisationRepository();
        $organisation = $organisationRepository->find($request->input('organisation_id'));
        if (is_null($organisation)) {
            throw new \Exception('invalid organisation provided');
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

//        // Move to attach endpoint
//        if ($request->input('group_form_ids')) {
//            $instrument->groupForms()->syncWithoutDetaching($request->input('group_form_ids'));
//        }
//
//        // Move to attach endpoint
//        if ($request->input('available_region_ids')) {
//            $instrument->availableRegions()->syncWithoutDetaching($request->input('available_region_ids'));
//        }
//        if ($request->input('available_township_ids')) {
//            $instrument->availableTownships()->syncWithoutDetaching($request->input('available_township_ids'));
//        }
//        if ($request->input('available_neighbourhood_ids')) {
//            $instrument->availableNeighbourhoods()->syncWithoutDetaching($request->input('available_neighbourhood_ids'));
//        }

        return $instrument;
    }
}
