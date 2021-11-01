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
            'is_nationally_available' => (bool) $this->is_nationally_available,

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
            'location_description' => $this->location_description,
            'work_agreements' => $this->work_agreements,
            'application_instructions' => $this->application_instructions,
            'intenstiy_hours_per_week' => $this->intenstiy_hours_per_week,
            'total_duration_value' => $this->total_duration_value,
            'total_duration_unit' => $this->total_duration_unit,
            'total_duration_unit_key' => $this->raw_total_duration_unit,
            'total_costs' => $this->total_costs,
            'intensity_duration_costs_description' => $this->intensity_duration_costs_description,

            // auxilary
            'import_mark' => $this->import_mark,

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
//            'providers' => ProviderResource::many($this->providers),
            'address' => AddressResource::one($this->address),
            'addresses' => AddressResource::many($this->addresses),
            'contacts' => ContactResource::many($this->contacts),

            'areas' => AreaResource::many($this->areas),
            'available_areas' => AreaResource::many($this->availableAreas),
            'regions' => RegionResource::many($this->availableRegions),
            'townships' => TownshipResource::many($this->availableTownships),
            'townshipParts' => TownshipPartResource::many($this->availableTownshipParts),


            // v1

            // descriptions
            'short_description' => $this->short_description,
            'description' => $this->description,
            'conditions' => $this->conditions,

//            'location' => $this->location,

            // info section
            'duration' => $this->duration,
            'duration_unit' => $this->duration_unit,
            'costs' => $this->costs,
            'costs_unit' => $this->costs_unit,

            // relations
            'themes' => ThemeResource::many($this->themes),
        ];
    }
}
