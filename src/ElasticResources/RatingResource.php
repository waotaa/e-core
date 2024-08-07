<?php

namespace Vng\EvaCore\ElasticResources;

class RatingResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'AlgemeneScore' => $this->general_score,
            'AuteurBeoordeling' => $this->author,
            'DatBeoordeling' => $this->formatDate($this->created_at),
            'EmailadresAuteurBeoordeling' => $this->email,
            'ResultaatScore' => $this->result_score,
            'ToelAlgemeneScore' => $this->general_explanation,
            'ToelResultaatScore' => $this->result_explanation,
            'ToelUitvoeringsScore' => $this->execution_explanation,
            'UitvoeringsScore' => $this->execution_score,

            'InstrumentWerknemersdienstverlening' => InstrumentWerknemersdienstverleningResource::one($this->whenLoaded('instrument')),

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
            'instrument' => InstrumentWerknemersdienstverleningResource::one($this->whenLoaded('instrument')),
            'professional' => ProfessionalResource::one($this->whenLoaded('professional')),

            // shared for dashboard
            'email' => $this->email,
            'professional_email' => $this->whenLoaded('professional', $this->professional?->email),
        ];
    }
}
