<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Illuminate\Support\Str;
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
        $endpoint = ElasticCurl::getPathForEndpoint($this->getRoleEndpoint());

        $curl->put($endpoint, $this->getRoleRequestBody());
    }

    public function getRoleConsoleInput()
    {
        return
            'PUT ' . $this->getRoleEndpoint() . PHP_EOL .
            json_encode($this->getRoleRequestBody(), JSON_PRETTY_PRINT);
    }

    private function getRoleEndpoint()
    {
        return 'kbn:api/security/role/' . $this->getRoleName();
    }

    private function getRoleName(): string
    {
        $environmentName = $this->environment->getAttribute('slug');
        return 'view-' . $environmentName;
    }

    #[ArrayShape(['elasticsearch' => "array", 'kibana' => "array"])]
    private function getRoleRequestBody(): array
    {
        $environmentSlug = $this->environment->getAttribute('slug');

        return [
            'elasticsearch' => [
                'indices' => [
                    $this->getRoleIndexPermissionInteraction($environmentSlug),
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
    private function getRoleIndexPermissionInteraction(string $environmentSlug): array
    {
        return [
            'names' => [
                'eva-prod*-*interaction'
            ],
            'privileges' => [
                'read',
                'view_index_metadata'
            ],
//            'query' => "{\"match_phrase\": {\"location.url\":\"${$environmentUrl}\"}}",
            'query' => '{"match": {"environment.slug.keyword":"'.$environmentSlug.'"}}',
            'allow_restricted_indices' => false
        ];
    }

    #[ArrayShape(['names' => "string[]", 'privileges' => "string[]", 'query' => "string", 'allow_restricted_indices' => "false"])]
    private function getRoleIndexPermissionInstrument(string $environmentSlug): array
    {
        return [
            'names' => [
                'eva-prod-centraal-instruments'
            ],
            'privileges' => [
                'read',
                'view_index_metadata'
            ],
//            'query' => "{\"match\": {\"organisation.slug.keyword\":\"${$organisationSlug}\"}}",
            'query' => '{"match": {"organisation.featuringEnvironment.slug.keyword":"'.$environmentSlug.'"}}"',
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
            'query' => '{"match": {"slug.keyword":"'.$environmentSlug.'"}}"',
            'allow_restricted_indices' => false
        ];
    }

    public function getUserConsoleInput()
    {
        return
            'POST ' . $this->getUserEndpoint() . PHP_EOL .
            json_encode($this->getUserRequestBody(), JSON_PRETTY_PRINT);
    }

    private function getUserEndpoint()
    {
        return '/_security/user/' . $this->environment->getAttribute('name');
    }

    private function getUserRequestBody(): array
    {
        return [
            'password' => Str::random(),
            'roles' => [
                $this->getRoleName()
            ],
        ];
    }
}