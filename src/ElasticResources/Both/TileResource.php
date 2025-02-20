<?php

namespace Vng\EvaCore\ElasticResources\Both;

use Vng\EvaCore\Helpers\Codelijsten;

class TileResource extends ElasticResource
{
    public function toArray()
    {
        return [
            // >> SGR
            'CdWerklandschapTegel' => $this->code,
            'NaamWerklandschapTegel' => Codelijsten::getWerklandschapTegelName($this->code) ?? $this->name, // AN..200

            'ToelNaamWerklandschaptegel' => $this->sub_title,
            'SlugWerklandschaptegel' => $this->key,
            'KorteOmsWerklandschaptegel' => $this->excerpt,
            'OmsWerklandschaptegel' => $this->description,

            // >> Current
            'id' => $this->id,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'deleted_at' => $this->formatDate($this->deleted_at),

            'name'  => $this->name,
            'sub_title'  => $this->sub_title,
            'excerpt'  => $this->excerpt,
            'description'  => $this->description,
            'crisis_description'  => $this->crisis_description,
            'crisis_services'  => $this->crisis_services,
            'list'  => $this->list,
            'key'  => $this->key,
            'position'  => $this->position,
        ];
    }
}
