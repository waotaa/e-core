<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Vng\EvaCore\Models\Environment;

class KibanaService
{
    public function __construct(
        private Environment $environment,
        private ElasticApiService $elasticApiService
    ){
    }

    public static function make(Environment $environment): self
    {
        $elasticApiService = new ElasticApiService();
        return new self($environment, $elasticApiService);
    }

    public function ensureKibanaSetup()
    {
        try {
            $this->updateOrCreateKibanaRoles();
        } catch (\Exception $e) {
            // exit the attempt
            return;
        }

        $user = $this->updateOrCreateKibanaUser();
        if (!is_null($user)) {
            $this->environment->update([
                'dashboard_username' => $user['username'],
                'dashboard_password' => $user['password'],
            ]);
        }
    }

    /**
     * @throws \Exception
     */
    public function updateOrCreateKibanaRoles(): void
    {
        try {
            $endpoint = 'kbn:api/security/role/' . $this->getRoleName();
            $requestBody = $this->getRoleRequestBody();
            $this->elasticApiService->put($endpoint, $requestBody);
        } catch (\Exception $e) {
            // Could add additional exception handling
            throw $e;
        }
    }

    #[ArrayShape(['username' => "string", 'password' => "string"])]
    public function updateOrCreateKibanaUser(): ?array
    {
        try {
            $username = $this->getUserName();
            $password = Str::random(12);
            $endpoint = '/_security/user/' . $username;
            $requestBody = [
                'password' => $password,
                'roles' => [
                    $this->getRoleName()
                ],
            ];
            $this->elasticApiService->put($endpoint, $requestBody);

            return [
                'username' => $username,
                'password' => $password,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @deprecated use api method instead
     *
     * @return string
     */
    public function getRoleConsoleInput()
    {
        return
            'PUT kbn:api/security/role/' . $this->getRoleName() . PHP_EOL .
            json_encode($this->getRoleRequestBody(), JSON_PRETTY_PRINT);
    }

    private function getRoleName(): string
    {
        $environmentSlug = $this->environment->getAttribute('slug');
        return 'view_' . $environmentSlug;
    }

    private function getUserName(): string
    {
        $environmentSlug = $this->environment->getAttribute('slug');
        return $environmentSlug;
//        return 'beheerder-' . $environmentSlug;
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
                'cluster' => []
            ],
            'kibana' => [
                [
                    "base" => [],
                    "feature" => [
                        "dashboard" => [
                            "minimal_read",
                            "store_search_session",
                            "generate_report",
                        ],
                        "visualize" => [
                            "minimal_read",
                            "generate_report"
                        ]
                    ],
                    "spaces" => [
                        "instrumenten",
                        "interactie"
                    ]
                ]
            ]
        ];
    }

    #[ArrayShape(['names' => "string[]", 'privileges' => "string[]", 'query' => "string", 'allow_restricted_indices' => "false"])]
    private function getRoleIndexPermissionInteraction(string $environmentSlug): array
    {
        return [
            'names' => [
                'eva-prod-*_interaction'
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
                'eva-production-centraal-instruments'
            ],
            'privileges' => [
                'read',
                'view_index_metadata'
            ],
//            'query' => "{\"match\": {\"organisation.slug.keyword\":\"${$organisationSlug}\"}}",
            'query' => '{"match": {"organisation.featuringEnvironments.slug.keyword":"'.$environmentSlug.'"}}',
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
            'query' => '{"match": {"slug.keyword":"'.$environmentSlug.'"}}',
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
        return '/_security/user/' . str_replace(' ', '-', $this->environment->getAttribute('name'));
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