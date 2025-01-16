<?php

namespace Vng\EvaCore\ElasticResources\SGR;

use Vng\EvaCore\Models\Faq;

class FaqResource extends ElasticResource
{
    public function toArray()
    {
        return [
            'VraagFaq' => $this->question,
            'AntwoordFaq' => $this->answer,
            'TypeFaq' => $this->type,
            'IsPortaalVraag' => $this->type == Faq::TYPE_PORTAAL,
            'IsBeheerVraag' => $this->type == Faq::TYPE_BEHEER,
            'CategoryFaq' => $this->category,
        ];
    }
}

