<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Services\ElasticSearch\Clients\ElasticBehaviourClientBuilder;

class BehaviourService
{
    const INDEX_GENERAL = 'general_interaction';
    const INDEX_RESULT = 'result_interaction';
    const INDEX_SEARCH = 'search_interaction';
    const INDEX_SHARE = 'share_interaction';

    public function __construct(
        private Environment $environment,
        private ElasticsearchDocumentService $elasticsearchDocumentService
    ){
    }

    public static function make(Environment $environment): self
    {
        $elasticsearchDocumentService = new ElasticsearchDocumentService();
        $elasticsearchDocumentService->setClient(ElasticBehaviourClientBuilder::make());
        return new self($environment, $elasticsearchDocumentService);
    }

    public function getAllBehaviour()
    {
        $query = [
            'term' => [
                'environment.slug' => $this->environment->getAttribute('slug'),
            ],
        ];
        return $this->elasticsearchDocumentService->scrollSearch($this->getGeneralIndex(), $query);
    }

    public function getBehaviourLast30Days()
    {
        $query = [
            'bool' => [
                'filter' => [
                    [
                        'term' => [
                            'environment.slug' => $this->environment->getAttribute('slug'),
                        ],
                    ],
                    [
                        'range' => [
                            'timestamp' => [
//                                'gte' => 'now-1M/M', // Begin van vorige maand
//                                'lt' => 'now/M',     // Begin van deze maand

                                'gte' => 'now-30d/d', // Vanaf 30 dagen geleden, vanaf middernacht
                                'lte' => 'now/d',     // Tot vandaag, tot middernacht

                                'time_zone' => 'Europe/Amsterdam',
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return $this->elasticsearchDocumentService->scrollSearch($this->getGeneralIndex(), $query);
    }

    private function getGeneralIndex(): string
    {
        return $this->getIndexPrefix() . '-'  . $this->environment->slug . '-' . $this::INDEX_GENERAL;
    }

    private function getIndexPrefix(): ?string
    {
        return config('elastic.instances.behaviour.prefix');
    }
}