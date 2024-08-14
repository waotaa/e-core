<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Helpers\Codelijsten;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Services\ModelHelpers\InstrumentHelper;
use Illuminate\Support\Str;

class InstrumentWerknemersdienstverleningResource extends ElasticResource
{
    /** @var Instrument */
    protected $resource;

    public function toArray()
    {
        return [
            // >> SGR
            'AantUrenIntensiteitPerWeek' => $this->intensity_hours_per_week,    // N..4
            'BedrTotaalKosten' => [
                'CdMunteenheid' => 'EUR',
                'CdPositiefNegatief' => '+',
                'WaardeBedr' => $this->total_costs,
            ],
            'CdEenheidDuurTraject' => Codelijsten::getTrajectDuurEenheidCode($this->total_duration_unit),
//            'EenheidDuurTraject' => $this->total_duration_unit,
            'DuurTraject' => $this->total_duration_value,               // N..4
            'OmsDoelInstrument' => $this->aim,                          // AN..320 - 4600
            'OmsOnderscheidendeAanpak' => $this->distinctive_approach,  // AN..320 - 3891
            'OmsWerkafspraken' => $this->work_agreements,               // AN..320 - 5374
            'SamenvattingInstrument' => $this->summary,                 // AN..320 - 2035 (validatie zegt max 500)
            'ToelDoelgroep' => $this->target_group_description,         // AN..320 - 7023
            'ToelDuurTraject' => $this->duration_description,           // AN..320 - 1260
            'ToelIntensiteit' => $this->intensity_description,          // AN..320 - 1863
            'ToelKosten' => $this->costs_description,                   // AN..320 - 2403
            'ToelWerkwijzeInstrument' => $this->method,                 // AN..320 - 12667
            'OmsAanmeldinstructgies' => $this->application_instructions,// meeste karakters 11480, meeste zitten onder de 5000
            'OmsVoorwaardenDeelname' => $this->participation_conditions,// meeste karakters 7886
            'OmsSamenwerkingsPartners' => $this->cooperation_partners,  // meeste karakters 1690
            'OmsAanvullendeInfo' => $this->additional_information,      // meeste karakters 12065

            'Instrument' => InstrumentResource::one($this->resource),

            'Aanbieder' => ProviderResource::one($this->provider),
            'Beoordeling' => RatingResource::many($this->ratings),
            'Contactpersoon' => ContactResource::many($this->contacts),
            'Doelgroep' => TargetGroupResource::many($this->targetGroups),
            'Download' => DownloadResource::many($this->downloads),
            'Groepsvorm' => GroupFormResource::many($this->groupForms),
            'Klantkenmerk' => ClientCharacteristicResource::many($this->clientCharacteristics),
            'Link' => LinkResource::many($this->links),
            'Registratiecode' => RegistrationCodeResource::many($this->registrationCodes),
            'Uitvoeringslocatie' => LocationResource::many($this->locations),
            'Uitvoeringsvorm' => ImplementationResource::one($this->implementation),
            'Video' => VideoResource::many($this->videos),
            'WerklandschapTegel' => TileResource::many($this->tiles),

            // todo: beschikbaarheid?

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => (string) Str::slug($this->name),
            'publish' => $this->is_active,
            'publish_from' => $this->formatDate($this->publish_from),
            'publish_to' => $this->formatDate($this->publish_to),
            'published' => InstrumentHelper::create($this->resource)->isPublished(),
            'complete' => InstrumentHelper::create($this->resource)->isComplete(),

            // v2

            // general information
            'aim' => $this->aim,
            'summary' => $this->summary,
            'method' => $this->method,
            'distinctive_approach' => $this->distinctive_approach,
            'target_group_description' => $this->target_group_description,
            'participation_conditions' => $this->participation_conditions,
            'cooperation_partners' => $this->cooperation_partners,
            'additional_information' => $this->additional_information,

            // practical information
            'work_agreements' => $this->work_agreements,
            'application_instructions' => $this->application_instructions,
            'intensity_hours_per_week' => $this->intensity_hours_per_week,
            'total_duration_value' => $this->total_duration_value,
            'total_duration_unit' => $this->total_duration_unit,
            'total_duration_unit_key' => $this->raw_total_duration_unit,
            'total_duration_hours' => $this->total_duration_hours, // calculated value
            'total_costs' => $this->total_costs,
            'total_costs_whole_number' => (int) round($this->total_costs),
            'costs_description' => $this->costs_description,
            'duration_description' => $this->duration_description,
            'intensity_description' => $this->intensity_description,

            // auxilary
            'import_mark' => $this->import_mark,

            // computed
            'is_national' => $this->resource->isNational(),
            'is_regional' => $this->resource->isRegional(),
            'is_local' => $this->resource->isLocal(),
            'reach' => $this->resource->getReach(),

            // relations
            'organisation' => OrganisationResource::one($this->organisation),
            'implementation' => ImplementationResource::one($this->implementation),
            'group_forms' => GroupFormResource::many($this->groupForms),
            'locations' => LocationResource::many($this->locations),

            'registration_codes' => RegistrationCodeResource::many($this->registrationCodes),
            'rating' => InstrumentHelper::create($this->resource)->getAverageRatings(),
            'ratings' => RatingResource::many($this->ratings),

            'tiles' => TileResource::many($this->tiles),
            'tiles_count' => count($this->tiles),
            'target_groups' => TargetGroupResource::many($this->targetGroups),
            'target_groups_count' => count($this->targetGroups),
            'client_characteristics' => ClientCharacteristicResource::many($this->clientCharacteristics),
            'client_characteristics_count' => count($this->clientCharacteristics),

            'links' => LinkResource::many($this->links),
            'videos' => VideoResource::many($this->videos),
            'downloads' => DownloadResource::many($this->downloads),

            'provider' => ProviderResource::one($this->provider),
            'contacts' => ContactResource::many($this->contacts),

            'available_areas' => AreaInterfaceResource::many($this->availableAreas),
            'available_areas_all' => AreaInterfaceResource::many($this->allAvailableAreas),
            'available_areas_townships' => AreaInterfaceResource::many($this->allAvailableTownships),

            // specified availability
            'available_areas_specified' => AreaInterfaceResource::many($this->specifiedAvailableAreas),
            'available_regions' => RegionResource::many($this->availableRegions),
            'available_townships' => TownshipResource::many($this->availableTownships),
            'available_neighbourhoods' => NeighbourhoodResource::many($this->availableNeighbourhoods),

            'parent_instrument' => InstrumentWerknemersdienstverleningResource::one($this->whenLoaded('parentInstrument'))
        ];
    }
}
