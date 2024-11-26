<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Helpers\Codelijsten;
use Vng\EvaCore\Models\Instrument;

class InstrumentWerknemersdienstverleningResource extends ElasticResource
{
    /** @var Instrument */
    protected $resource;

    public function toArray()
    {
        return [
            'Instrument' => InstrumentResource::one($this->resource),

            'AantUrenIntensiteitPerWeek' => $this->intensity_hours_per_week,    // N..4
            'BedrTotaalKosten' => [
                'CdMunteenheid' => 'EUR',
                'CdPositiefNegatief' => '+',
                'WaardeBedr' => $this->total_costs,
            ],
            'CdEenheidDuurTraject' => Codelijsten::getTrajectDuurEenheidCode($this->total_duration_unit),
//            'EenheidDuurTraject' => $this->total_duration_unit,
            'DuurTraject' => $this->total_duration_value,               // N..4
            'DuurTrajectInUren' => (float) $this->total_duration_hours, // N..4
            'OmsDoelInstrument' => $this->aim,                          // AN..320
            'OmsOnderscheidendeAanpak' => $this->distinctive_approach,  // AN..320 - 3891
            'OmsWerkafspraken' => $this->work_agreements,               // AN..320 - 5374
            'SamenvattingInstrument' => $this->summary,                 // AN..320 - 2035 (validatie zegt max 500)
            'ToelDoelgroep' => $this->target_group_description,         // AN..320 - 7023
            'ToelDuurTraject' => $this->duration_description,           // AN..320 - 1260
            'ToelIntensiteit' => $this->intensity_description,          // AN..320 - 1863
            'ToelKosten' => $this->costs_description,                   // AN..320 - 2403
            'ToelWerkwijzeInstrument' => $this->method,                 // AN..320 - 12667
            'OmsAanmeldinstructies' => $this->application_instructions,// meeste karakters 11480, meeste zitten onder de 5000
            'OmsVoorwaardenDeelname' => $this->participation_conditions,// meeste karakters 7886
            'OmsSamenwerkingsPartners' => $this->cooperation_partners,  // meeste karakters 1690
            'OmsAanvullendeInfo' => $this->additional_information,      // meeste karakters 12065

            'Groepsvorm' => GroupFormResource::many($this->groupForms),
            'Klantkenmerk' => ClientCharacteristicResource::many($this->clientCharacteristics),
            'Uitvoeringsvorm' => ImplementationResource::many($this->implementations),

            'Beoordeling' => RatingResource::many($this->ratings),
        ];
    }
}
