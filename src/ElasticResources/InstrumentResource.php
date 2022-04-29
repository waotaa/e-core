<?php

namespace Vng\EvaCore\ElasticResources;

use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Services\ModelHelpers\InstrumentHelper;
use Illuminate\Support\Str;

class InstrumentResource extends ElasticResource
{
    /** @var Instrument */
    protected $resource;

    public function toArray()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => (string) Str::slug($this->name),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'publish' => $this->is_active,
            'publish_from' => $this->publish_from,
            'publish_to' => $this->publish_to,

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

            // relations
            'owner' => OwnerResource::one($this->owner),
            'implementation' => ImplementationResource::one($this->implementation),
            'group_forms' => GroupFormResource::many($this->groupForms),
            'locations' => LocationResource::many($this->locations),

            'registration_codes' => RegistrationCodeResource::many($this->registrationCodes),
            'rating' => InstrumentHelper::create($this->resource)->getAverageRatings(),
            'ratings' => RatingResource::many($this->ratings),

            'tiles' => TileResource::many($this->tiles),
            'target_groups' => TargetGroupResource::many($this->targetGroups),
            'client_characteristics' => ClientCharacteristicResource::many($this->clientCharacteristics),

            'links' => LinkResource::many($this->links),
            'videos' => VideoResource::many($this->videos),
            'downloads' => DownloadResource::many($this->downloads),

            'provider' => ProviderResource::one($this->provider),
            'contacts' => ContactResource::many($this->contacts),

            'available_areas' => AreaInterfaceResource::many($this->availableAreas),
            'available_areas_all' => AreaInterfaceResource::many($this->allAvailableAreas),

            // specified availability
            'available_areas_specified' => AreaInterfaceResource::many($this->specifiedAvailableAreas),
            'available_regions' => RegionResource::many($this->availableRegions),
            'available_townships' => TownshipResource::many($this->availableTownships),
            'available_neighbourhoods' => NeighbourhoodResource::many($this->availableNeighbourhoods),
        ];
    }
}
