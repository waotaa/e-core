<?php

namespace Vng\EvaCore\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    public function toArray($request)
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
            'professional_email' => $this->professional ? $this->professional->email : null,

            'created_at' => $this->created_at,

            // relations
            'professional' => ProfessionalResource::make($this->professional),
        ];
    }
}
