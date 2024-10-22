<?php

namespace Vng\EvaCore\ElasticResources\SGR;

class RatingResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'AuteurBeoordeling' => $this->author,                       // AN..200
            'EmailadresAuteurBeoordeling' => $this->email,              // AN..320
            'AlgemeneScore' => $this->general_score,                    // N1
            'ToelAlgemeneScore' => $this->general_explanation,          // AN..320
            'ResultaatScore' => $this->result_score,                    // N1
            'ToelResultaatScore' => $this->result_explanation,          // AN..320
            'UitvoeringsScore' => $this->execution_score,               // N1
            'ToelUitvoeringsScore' => $this->execution_explanation,     // AN..320
            'DatTijdBeoordeling' => $this->formatDate($this->created_at),   // DATUMTIJDSTIP

            'InstrumentWerknemersdienstverlening' => InstrumentResource::one($this->whenLoaded('instrument')),
        ];
    }
}
