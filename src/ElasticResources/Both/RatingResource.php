<?php

namespace Vng\EvaCore\ElasticResources\Both;

class RatingResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
//            'id' => $this->id,
            'AuteurBeoordeling' => $this->author,                       // AN..200
            'EmailadresAuteurBeoordeling' => $this->email,              // AN..320
            'AlgemeneScore' => (int) $this->general_score,                    // N1
            'ToelAlgemeneScore' => $this->general_explanation,          // AN..320
            'ResultaatScore' => (int) $this->result_score,                    // N1
            'ToelResultaatScore' => $this->result_explanation,          // AN..320
            'UitvoeringsScore' => (int) $this->execution_score,               // N1
            'ToelUitvoeringsScore' => $this->execution_explanation,     // AN..320
            'DatTijdBeoordeling' => $this->formatDate($this->created_at),   // DATUMTIJDSTIP

            'InstrumentWerknemersdienstverlening' => InstrumentResource::one($this->whenLoaded('instrument')),

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'author' => $this->author,
            'general_score' => $this->general_score,
            'general_explanation' => $this->general_explanation,
            'result_score' => $this->result_score,
            'result_explanation' => $this->result_explanation,
            'execution_score' => $this->execution_score,
            'execution_explanation' => $this->execution_explanation,

            'instrument_id' => $this->instrument_id,

            // relations
            'instrument' => InstrumentResource::one($this->whenLoaded('instrument')),
            'professional' => ProfessionalResource::one($this->whenLoaded('professional')),

            // shared for dashboard
            'email' => $this->email,
            'professional_email' => $this->whenLoaded('professional', $this->professional?->email),
        ];
    }
}
