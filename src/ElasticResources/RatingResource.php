<?php

namespace Vng\EvaCore\ElasticResources;

class RatingResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'general_score' => $this->general_score,
            'general_explanation' => $this->general_explanation,
            'result_score' => $this->result_score,
            'result_explanation' => $this->result_explanation,
            'execution_score' => $this->execution_score,
            'execution_explanation' => $this->execution_explanation,

            'instrument_id' => $this->instrument->id,
            'professional_email' => $this->professional->email,

            'created_at' => $this->created_at,

            // relations
            'professional' => ProfessionalResource::one($this->professional),
        ];
    }
}
