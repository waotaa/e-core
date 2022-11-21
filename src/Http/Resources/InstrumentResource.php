<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Vng\EvaCore\Services\ModelHelpers\InstrumentHelper;

class InstrumentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => (string) Str::slug($this->name),
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
            'owner' => OwnerResource::make($this->owner),
            'organisation' => OrganisationResource::make($this->organisation),
            'implementation' => ImplementationResource::make($this->implementation),
            'group_forms' => GroupFormResource::collection($this->groupForms),
            'locations' => LocationResource::collection($this->locations),

            'registration_codes' => RegistrationCodeResource::collection($this->registrationCodes),
            'rating' => InstrumentHelper::create($this->resource)->getAverageRatings(),
            'ratings' => RatingResource::collection($this->ratings),

            'tiles' => TileResource::collection($this->tiles),
            'tiles_count' => count($this->tiles),
            'target_groups' => TargetGroupResource::collection($this->targetGroups),
            'target_groups_count' => count($this->targetGroups),
            'client_characteristics' => ClientCharacteristicResource::collection($this->clientCharacteristics),
            'client_characteristics_count' => count($this->clientCharacteristics),

            'links' => LinkResource::collection($this->links),
            'videos' => VideoResource::collection($this->videos),
            'downloads' => DownloadResource::collection($this->downloads),

            'provider' => ProviderResource::make($this->provider),
            'contacts' => ContactResource::collection($this->contacts),

            'available_areas' => AreaInterfaceResource::collection($this->availableAreas),
            'available_areas_all' => AreaInterfaceResource::collection($this->allAvailableAreas),

            // specified availability
            'available_areas_specified' => AreaInterfaceResource::collection($this->specifiedAvailableAreas),
            'available_regions' => RegionResource::collection($this->availableRegions),
            'available_townships' => TownshipResource::collection($this->availableTownships),
            'available_neighbourhoods' => NeighbourhoodResource::collection($this->availableNeighbourhoods),

            'mutations' => MutationResource::collection($this->mutations),
            'parent_instrument' => InstrumentResource::make($this->whenLoaded($this->parentInstrument))
        ];
    }
}
