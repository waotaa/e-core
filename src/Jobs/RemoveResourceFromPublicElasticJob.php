<?php

namespace Vng\EvaCore\Jobs;

class RemoveResourceFromPublicElasticJob extends RemoveResourceFromElasticJob
{
    use PublicElasticClientTrait;
}
