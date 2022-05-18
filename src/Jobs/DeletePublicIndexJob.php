<?php

namespace Vng\EvaCore\Jobs;

class DeletePublicIndexJob extends DeleteIndexJob
{
    use PublicElasticClientTrait;
}
