<?php

namespace Vng\EvaCore\Jobs;

use Elastic\Elasticsearch\Client;
use Vng\EvaCore\Models\Environment;

// https://www.elastic.co/guide/en/kibana/current/api.html
// https://www.elastic.co/guide/en/kibana/current/console-kibana.html

class SetupKibanaAccount extends ElasticJob
{
    public string $index;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {

    }

    /**
     * Create a user (for the created role)
     *
     * https://www.elastic.co/guide/en/elasticsearch/reference/current/security-api-put-user.html
     */
    public function createUser(Environment $environment)
    {
        $environmentName = $environment->getAttribute('name');
        $environmentSlug = $environment->getAttribute('slug');

        /** @var Client $elasticSearchClient */
        $elasticSearchClient = $this->getClient();
        $elasticSearchClient->security()->putUser([
            'username' => $environmentName,
            'body' => [
                'roles' => ['view_' . $environmentName],
                'full_name' => '',
                'email' => '',
                'password' => '' // IS REQUIRED
            ]
        ]);
    }
}
