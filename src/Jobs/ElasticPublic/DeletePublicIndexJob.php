<?php

namespace Vng\EvaCore\Jobs\ElasticPublic;

use Vng\EvaCore\Jobs\DeleteIndexJob;

class DeletePublicIndexJob extends DeleteIndexJob
{
    use PublicElasticClientTrait;
}
