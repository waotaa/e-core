<?php

namespace Vng\EvaCore\Commands\Dev;

use Vng\EvaCore\Commands\EnvironmentArgument;
use Vng\EvaCore\Models\Environment;
use Illuminate\Console\Command;
use Vng\EvaCore\Services\ElasticSearch\BehaviourService;

class GenerateReport extends Command
{
    use EnvironmentArgument;

    protected $signature = 'dev:report {environment?}';
    protected $description = 'Create a usage report';

    public function handle(): int
    {
        $this->getOutput()->writeln('Generating report');

        $environmentArgument = $this->argument('environment');
        $environments = $this->getTargetedEnvironments($environmentArgument);

        foreach ($environments as $environment) {
            $this->getAllBehaviourDocuments($environment);
        }

        return 0;
    }

    public function getAllBehaviourDocuments(Environment $environment)
    {
        $result = BehaviourService::make($environment)->getAllBehaviour();
        dd($result);
    }
}
