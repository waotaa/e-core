<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Illuminate\Support\Facades\Log;
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
//            throw $e;
//             exit the attempt
            return;
        }

        $user = $this->updateOrCreateKibanaUser();
        if (!is_null($user)) {
            $this->environment->updateQuietly([
                'dashboard_username' => $user['username'],
                'dashboard_password' => $user['password'],
            ]);
        }
    }

    private function getRoleName(): string
    {
        $environmentSlug = $this->environment->getAttribute('slug');
        return 'view_' . $environmentSlug;
    }

    private function getUserName(): string
    {
        return str_replace(' ', '-', $this->environment->getAttribute('name'));
    }

    public function updateOrCreateKibanaRoles(): void
    {
        try {
            $endpoint = '/_security/role/' . $this->getRoleName();
            $requestBody = $this->getRoleRequestBody();
            $result = $this->elasticApiService->put($endpoint, $requestBody);
            Log::debug('kibana roles result', $result);
        }  catch (\Exception $e) {
            Log::error($e);
            throw $e;
//            return null;
        }
    }

    private function getRoleRequestBody(): array
    {
        $environmentSlug = $this->environment->getAttribute('slug');

        return [
            "cluster" => ["all"],
            'applications' => [
                [
                    'application' => 'kibana',
                    'privileges' => [
                        'dashboard.minimal_read',
                        'dashboard.store_search_session',
                        'dashboard.generate_report',
                        'visualize.minimal_read',
                        'visualize.generate_report',
                    ],
                    'resources' => [
                        'space/instrumenten',
                        'space/interactie'
                    ],
                ],
            ],
            'indices' => [
                $this->getRoleIndexPermissionInteraction($environmentSlug),
                $this->getRoleIndexPermissionInstrument($environmentSlug),
                $this->getRoleIndexPermissionEnvironment($environmentSlug),
            ],
        ];
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
            $result = $this->elasticApiService->put($endpoint, $requestBody);
            Log::debug('kibana user creation result', $result);
            $created = $result['created'] ? '' : ' NOT';
            Log::info('User is' . $created . ' created? ');
            return [
                'username' => $username,
                'password' => $password,
            ];
        }  catch (\Exception $e) {
            Log::error($e);
            throw $e;
//            return null;
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
            json_encode($this->getCliRoleRequestBody(), JSON_PRETTY_PRINT);
    }

    private function getCliRoleRequestBody(): array
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
        return '/_security/user/' . $this->getUserName();
    }

    private function getUserRequestBody(): array
    {
        return [
            'password' => Str::random(12),
            'roles' => [
                $this->getRoleName()
            ],
        ];
    }
}