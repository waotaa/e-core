<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use JetBrains\PhpStorm\ArrayShape;
use Vng\EvaCore\Models\Environment;

class KibanaService
{
    private Environment $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public static function make(Environment $environment): self
    {
        return new self($environment);
    }

    public function putRoles()
    {
        $curl = ElasticCurl::make();
        $endpoint = ElasticCurl::getPathForEndpoint('kbn:api/security/role/' . $this->getRoleName());

        $curl->put($endpoint, $this->getRolesRequestBody());
    }

    private function getRoleName(): string
    {
        $environmentName = $this->environment->getAttribute('name');
        return 'view-' . $environmentName;
    }

    #[ArrayShape(['elasticsearch' => "array", 'kibana' => "array"])]
    private function getRolesRequestBody(): array
    {
        $environmentName = $this->environment->getAttribute('name');
        $environmentUrl = $environmentName . 'instrumentengids-eva.nl';

        $environmentSlug = $this->environment->getAttribute('slug');

        return [
            'elasticsearch' => [
                'indices' => [
                    $this->getRoleIndexPermissionInteraction($environmentUrl),
                    $this->getRoleIndexPermissionInstrument($environmentSlug),
                    $this->getRoleIndexPermissionEnvironment($environmentSlug),
                ],
                'cluster' => [],
                'metadata' => []
            ],
            'kibana' => [
                "base" => [],
                "feature" => [
                    "dashboard" => [
                        "minimal_read",
                        "store_search_session",
                        "generate_report",
                    ]
                ],
                "spaces" => [
                    "instrumenten",
                    "interactie"
                ]
            ],
        ];
    }

    #[ArrayShape(['names' => "string[]", 'privileges' => "string[]", 'query' => "string", 'allow_restricted_indices' => "false"])]
    private function getRoleIndexPermissionInteraction(string $environmentUrl): array
    {
        return [
            'names' => [
                'eva-prod*-*interaction'
            ],
            'privileges' => [
                'read',
                'view_index_metadata'
            ],
            'query' => "{\"match_phrase\": {\"location.url\":\"${$environmentUrl}\"}}",
            'allow_restricted_indices' => false
        ];
    }

    #[ArrayShape(['names' => "string[]", 'privileges' => "string[]", 'query' => "string", 'allow_restricted_indices' => "false"])]
    private function getRoleIndexPermissionInstrument(string $organisationSlug): array
    {
        return [
            'names' => [
                'eva-prod-centraal-instruments'
            ],
            'privileges' => [
                'read',
                'view_index_metadata'
            ],
            'query' => "{\"match\": {\"organisation.slug.keyword\":\"${$organisationSlug}\"}}",
            'allow_restricted_indices' => false
        ];
    }

    #[ArrayShape(['names' => "string[]", 'privileges' => "string[]", 'query' => "string", 'allow_restricted_indices' => "false"])]
    private function getRoleIndexPermissionEnvironment(string $environmentSlug): array
    {
        return [
            'names' => [
                'eva-production-centraal-environments'
            ],
            'privileges' => [
                'read',
                'view_index_metadata'
            ],
            'query' => "{\"match\": {\"slug.keyword\":\"${$environmentSlug}\"}}",
            'allow_restricted_indices' => false
        ];
    }
}