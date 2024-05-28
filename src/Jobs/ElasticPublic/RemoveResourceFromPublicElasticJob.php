<?php

namespace Vng\EvaCore\Jobs\ElasticPublic;

use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;

class RemoveResourceFromPublicElasticJob extends RemoveResourceFromElasticJob
{
    use PublicElasticClientTrait;
}
