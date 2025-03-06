<?php

namespace Vng\EvaCore\Commands\Dev;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Vng\EvaCore\Commands\EnvironmentArgument;
use Vng\EvaCore\Jobs\ElasticJob;
use Vng\EvaCore\Models\Environment;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Repositories\ProfessionalRepositoryInterface;
use Vng\EvaCore\Services\CSV\CsvGenerator;
use Vng\EvaCore\Services\ElasticSearch\BehaviourService;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchDocumentService;
use Vng\EvaCore\Services\ModelHelpers\InstrumentHelper;
use Vng\EvaCore\Services\Storage\InternalStorageService;

class GenerateReport extends Command
{
    use EnvironmentArgument;

    protected $signature = 'dev:report {environment?} {--i=1}';
    protected $description = 'Create a usage report';

    const LIMIT_ENVIRONMENT_COUNT = 20;

    public function handle(): int
    {
        $this->getOutput()->writeln('Generating report');

        $environmentArgument = $this->argument('environment');
        $environments = $this->getTargetedEnvironments($environmentArgument);

        if (is_null($environmentArgument)) {
            $iterationOption = (int) $this->option('i');
            $offset = ($iterationOption - 1) * self::LIMIT_ENVIRONMENT_COUNT; // Iteratie 1 geeft offset 0
            $environments = $environments->slice($offset, self::LIMIT_ENVIRONMENT_COUNT);
        }

        $headers = [
            'omgeving',
            'totaal in database',
            'gepubliceerd in database',
//            'compleet in database',
            'totaal in elastic',
            'gepubliceerd in elastic',
//            'compleet in elastic',
            'aantal acties totaal',
            'aantal acties laatst 30 dagen',
            'totaal professionals',
            'actieve professionals'
        ];

        $rows = [];

        foreach ($environments as $environment) {
            $csvRow = $this->getReportForEnvironment($environment);
            $rows[] = $csvRow;
        }

        // Maak een nieuwe CSV Generator instance
        $csvGenerator = new CsvGenerator();
        $csvContent = $csvGenerator
            ->setHeaders($headers)
            ->addRows($rows)
            ->generate();

        // Toon de CSV-inhoud in de console
        $this->info("CSV Inhoud:\n");
        $this->line($csvContent);

        $filename = 'csv-rapport-' . now()->format('Y-m-d') . '.csv';
        $filePath = InternalStorageService::make()->storeFile($csvContent, $filename);

        if ($filePath) {
            $this->info('CSV stored is S3 at: ' . $filePath);
        }

        return 0;
    }

    protected function getReportForEnvironment(Environment $environment)
    {
        $csvRowData = [];

        $csvRowData[] = $environment->name;
        $this->info('>> Environment - ' . $environment->name);

        $instrumentsTotalDB = $this->getInstrumentsTotalFromDB($environment);
        $csvRowData[] = count($instrumentsTotalDB);
        $this->line('total instruments in DB: '. count($instrumentsTotalDB));

        $instrumentsDBPublished = $this->getPublishedInstrumentsFromDB($environment);
        $csvRowData[] = count($instrumentsDBPublished);
        $this->line('published instruments in DB: '. count($instrumentsDBPublished));

//        $instrumentsDBComplete = $this->getCompleteInstrumentsFromDB($environment);
//        $csvRowData[] = count($instrumentsDBComplete);
//        $this->line('complete instruments in DB: '. count($instrumentsDBComplete));


        $instrumentsTotalElastic = $this->getInstrumentsTotalFromDashboard($environment);
        $csvRowData[] = count($instrumentsTotalElastic);
        $this->line('total instruments in Elastic: '. count($instrumentsTotalElastic));

        $instrumentsElasticPublished = $this->getPublishedInstrumentsFromDashboard($environment);
        $csvRowData[] = count($instrumentsElasticPublished);
        $this->line('published instruments in Elastic: '. count($instrumentsElasticPublished));

//        $instrumentsElasticComplete = $this->getCompleteInstrumentsFromDashboard($environment);
//        $csvRowData[] = count($instrumentsElasticComplete);
//        $this->line('complete instruments in Elastic: '. count($instrumentsElasticComplete));

        // behaviour
        $behaviourService = BehaviourService::make($environment);
        $this->line('Behaviour index: ' . $behaviourService->getGeneralIndex());
        $actionCount = $behaviourService->countGeneralInteraction();
        $csvRowData[] = $actionCount;
        $this->line('total actions ' . $actionCount);

        $actionsLast30Days = BehaviourService::make($environment)->getBehaviourLast30Days();
        $csvRowData[] = count($actionsLast30Days);
        $this->line('actions last 30 days ' . count($actionsLast30Days));

        // professionals
        $environmentProfessionals = $this->getTotalProfessionals($environment);
        $csvRowData[] = count($environmentProfessionals);
        $this->line('totaal professionals ' . count($environmentProfessionals));

        $actionsPerUserId = $this->getActiveProfessionalsLast30Days($actionsLast30Days);
        $csvRowData[] = count($actionsPerUserId);
        $this->line('active professionals ' . count($actionsPerUserId));

        return $csvRowData;
    }

    protected function getInstrumentsTotalFromDB(Environment $environment): Collection|array
    {
        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);
        $query = $instrumentRepo->builder();

        $featuredOrganisations = $environment->featuredOrganisations;
        $query->whereHas('organisation', function (Builder $query) use ($featuredOrganisations) {
            $query->whereIn('id', $featuredOrganisations->pluck('id'));
        });

        return $query->get();
    }

    protected function getPublishedInstrumentsFromDB(Environment $environment): Collection|array
    {
        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);
        $query = $instrumentRepo->builder();
        $query = InstrumentHelper::queryPublished($query);

        // filter for environment
        $featuredOrganisations = $environment->featuredOrganisations;
        $query->whereHas('organisation', function (Builder $query) use ($featuredOrganisations) {
            $query->whereIn('id', $featuredOrganisations->pluck('id'));
        });

        return $query->get();
    }

    protected function getCompleteInstrumentsFromDB(Environment $environment): Collection|array
    {
        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);
        $query = $instrumentRepo->builder();
        $query = InstrumentHelper::queryComplete($query);

        // filter for environment
        $featuredOrganisations = $environment->featuredOrganisations;
        $query->whereHas('organisation', function (Builder $query) use ($featuredOrganisations) {
            $query->whereIn('id', $featuredOrganisations->pluck('id'));
        });

        return $query->get();
    }


    protected function getInstrumentsTotalFromDashboard(Environment $environment): array
    {
        $query = [
            'bool' => [
                'filter' => [
                    $this->getElasticEnvironmentCondition($environment)
                ],
            ],
        ];

        $index = ElasticJob::prefixIndex('instruments');
        return ElasticsearchDocumentService::make()->scrollSearch($index, $query);
    }

    protected function getPublishedInstrumentsFromDashboard(Environment $environment): array
    {
        $query = [
            'bool' => [
                'filter' => [
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
                                ['range' => ['publish_to' => ['gte' => 'now/d', 'time_zone' => 'Europe/Amsterdam']]], // Inclusief publish to dag
//                                ['range' => ['publish_to' => ['gt' => 'now/d', 'time_zone' => 'Europe/Amsterdam']]], // Exclusief publish to dag
                                ['bool' => ['must_not' => ['exists' => ['field' => 'publish_to']]]],
                            ],
                        ],
                    ],
                    $this->getElasticEnvironmentCondition($environment)
                ],
            ],
        ];

        $index = ElasticJob::prefixIndex('instruments');
        return ElasticsearchDocumentService::make()->scrollSearch($index, $query);
    }

    protected function getCompleteInstrumentsFromDashboard(Environment $environment): array
    {
        $query = [
            'bool' => [
                'filter' => [
                    ['term' => ['complete' => true]],
                    $this->getElasticEnvironmentCondition($environment)
                ],
            ],
        ];

        $index = ElasticJob::prefixIndex('instruments');
        return ElasticsearchDocumentService::make()->scrollSearch($index, $query);
    }

    private function getElasticEnvironmentCondition(Environment $environment): array
    {
        $featuredOrganisations = $environment->featuredOrganisations;
        $environmentCondition = [
            'bool' => [
                'minimum_should_match' => 1,
                'should' => []
            ]
        ];

        foreach ($featuredOrganisations as $org) {
            $environmentCondition['bool']['should'][] = [
                'bool' => [
                    'must' => [
                        ['term' => ['organisation.slug.keyword' => $org->getSlugAttribute()]],
                        ['match' => ['organisation.type' => $org->getTypeAttribute()]],
                    ]
                ]
            ];
        }

        return $environmentCondition;
    }

    protected function getTotalProfessionals(Environment $environment)
    {
        /** @var ProfessionalRepositoryInterface $professionalReposiotory */
        $professionalRepository = app(ProfessionalRepositoryInterface::class);
        $query = $professionalRepository
            ->builder()
            ->where('environment_id', $environment->getKey());
        return $query->get();
    }

    public function getActiveProfessionalsLast30Days(array $actions): array
    {
        $userIdForActions = array_map(fn ($r) => $r['_source']['user']['id'] ?? null, $actions);
        // Verwijder mogelijke null waarden uit de array
        $userIdForActions = array_filter($userIdForActions, fn ($id) => !is_null($id));
        return array_count_values($userIdForActions);
    }
}
