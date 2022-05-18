<?php

namespace Vng\EvaCore\Jobs;

class DeletePublicIndexJob extends DeleteIndexJob
{
    use PublicElasticClientTrait;

    protected function getFullIndex(): string
    {
        return $this->index;
    }
}
