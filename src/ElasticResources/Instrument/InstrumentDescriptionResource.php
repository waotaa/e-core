<?php

namespace Vng\EvaCore\ElasticResources\Instrument;

use Vng\EvaCore\ElasticResources\AddressResource;
use Vng\EvaCore\ElasticResources\ClientCharacteristicResource;
use Vng\EvaCore\ElasticResources\ContactResource;
use Vng\EvaCore\ElasticResources\DownloadResource;
use Vng\EvaCore\ElasticResources\ElasticResource;
use Vng\EvaCore\ElasticResources\GroupFormResource;
use Vng\EvaCore\ElasticResources\ImplementationResource;
use Vng\EvaCore\ElasticResources\LinkResource;
use Vng\EvaCore\ElasticResources\LocationResource;
use Vng\EvaCore\ElasticResources\ProviderResource;
use Vng\EvaCore\ElasticResources\TargetGroupResource;
use Vng\EvaCore\ElasticResources\TileResource;
use Vng\EvaCore\ElasticResources\VideoResource;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Services\ModelHelpers\InstrumentHelper;
use Illuminate\Support\Str;

class InstrumentDescriptionResource extends ElasticResource
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
            'location_description' => $this->location_description,
            'work_agreements' => $this->work_agreements,
            'application_instructions' => $this->application_instructions,
            'intensity_hours_per_week' => $this->intensity_hours_per_week,
            'total_duration_value' => $this->total_duration_value,
            'total_duration_unit' => $this->total_duration_unit,
            'total_duration_unit_key' => $this->raw_total_duration_unit,

//            Don't share costs on public page
//            'total_costs' => $this->total_costs,
//            'costs_description' => $this->costs_description,
//            'duration_description' => $this->duration_description,
//            'intensity_description' => $this->intensity_description,

            // relations
            'implementation' => ImplementationResource::one($this->implementation),
            'group_forms' => GroupFormResource::many($this->groupForms),
            'locations' => LocationResource::many($this->locations),

            'tiles' => TileResource::many($this->tiles),
            'target_groups' => TargetGroupResource::many($this->targetGroups),
            'client_characteristics' => ClientCharacteristicResource::many($this->clientCharacteristics),

            'links' => LinkResource::many($this->links),
            'videos' => VideoResource::many($this->videos),
            'downloads' => DownloadResource::many($this->downloads),

            'provider' => ProviderResource::one($this->provider),
            'addresses' => AddressResource::many($this->addresses),
            'contacts' => ContactResource::many($this->contacts),

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
        ];
    }
}
