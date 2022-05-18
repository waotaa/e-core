<?php

namespace Vng\EvaCore\Jobs;

class RemoveResourceFromPublicElasticJob extends RemoveResourceFromElasticJob
{
    use PublicElasticClientTrait;

    protected function getFullIndex(): string
    {
        return $this->index;
    }
}
