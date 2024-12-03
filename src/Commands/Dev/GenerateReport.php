<?php

namespace Vng\EvaCore\Commands\Dev;

use Vng\EvaCore\Commands\EnvironmentArgument;
use Vng\EvaCore\Jobs\ElasticJob;
use Vng\EvaCore\Models\Environment;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Repositories\ProfessionalRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\BehaviourService;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchDocumentService;
use Vng\EvaCore\Services\ModelHelpers\InstrumentHelper;

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
            $this->getReportForEnvironment($environment);
            $this->getAllBehaviourDocuments($environment);
        }

        return 0;
    }

    protected function getReportForEnvironment(Environment $environment)
    {
        $this->info('>> Environment - ' . $environment->name);
        $instrumentsDB = $this->getPublishedInstrumentsFromDB();
        $this->line('published instruments in DB: '. count($instrumentsDB));

        $instrumentsElastic = $this->getPublishedInstrumentsFromDashboard();
        $this->line('published instruments in Elastic: '. count($instrumentsElastic));

        $instrumentsTotalDB = $this->getInstrumentsTotalFromDB();
        $this->line('total instruments in DB: '. count($instrumentsTotalDB));

        $instrumentsTotalElastic = $this->getInstrumentsTotalFromDashboard();
        $this->line('total instruments in Elastic: '. count($instrumentsTotalElastic));
    }

    public function getAllBehaviourDocuments(Environment $environment)
    {
        $result = BehaviourService::make($environment)->getAllBehaviour();
        $this->line('document count ' . count($result));
    }

    protected function getPublishedInstrumentsFromDB()
    {
        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);
        $query = $instrumentRepo->builder();
        $query = InstrumentHelper::queryPublished($query);
        // filter for environment?
        return $query->get();
    }

    protected function getPublishedInstrumentsFromDashboard()
    {
        $query = [
            'bool' => [
                'filter' => [
                    ['term' => ['complete' => true]],
                    ['term' => ['publish' => true]],
                    [
                        'bool' => [
                            'should' => [
                                ['range' => ['publish_from' => ['lte' => 'now/d', 'time_zone' => 'Europe/Amsterdam']]],
                                ['bool' => ['must_not' => ['exists' => ['field' => 'publish_from']]]],
                            ],
                        ],
                    ],
                    [
                        'bool' => [
                            'should' => [
                                ['range' => ['publish_to' => ['gte' => 'now/d', 'time_zone' => 'Europe/Amsterdam']]],
                                ['bool' => ['must_not' => ['exists' => ['field' => 'publish_to']]]],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $index = ElasticJob::prefixIndex('instruments');
        return ElasticsearchDocumentService::make()->scrollSearch($index, $query);
    }

    protected function getInstrumentsTotalFromDB()
    {
        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);
        $query = $instrumentRepo->builder();
        // filter for environment?
        return $query->get();
    }

    protected function getInstrumentsTotalFromDashboard()
    {
        $index = ElasticJob::prefixIndex('instruments');
        return ElasticsearchDocumentService::make()->scrollSearch($index);
    }

    protected function getTotalProfessionals(Environment $environment)
    {
        /** @var ProfessionalRepositoryInterface $professionalRepository */
        $professionalRepository = app(ProfessionalRepositoryInterface::class);
        $query = $professionalRepository
            ->builder()
            ->where('environment_id', $environment->getKey());
        return $query->get();
    }

    // actieve gebruikers elastic behaviour per maand - wat is actief?
    // actieve gebruikers elastic behaviour 60 dagen

    // aantal handelingen per maand

}
