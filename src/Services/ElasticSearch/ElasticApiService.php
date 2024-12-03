<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * This class offers a get and put method for making direct http requests (without SDK) to the kibana API
 */
class ElasticApiService
{

    public function get(string $endpoint)
    {
        $headers = $this->getCommonHeaders();
        $url = $this->getPathForEndpoint($endpoint);
        return Http::withHeaders($headers)->get($url);
    }

    /**
     * Perform an HTTP PUT request.
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function put(string $endpoint, array $data)
    {
        // Set the authentication headers
        $headers = $this->getCommonHeaders();
        $url = $this->getPathForEndpoint($endpoint);

        $response = Http::withHeaders($headers)->put($url, $data);

        if ($response->failed()) {
            $errorMessage = $response->body();
            throw new Exception($errorMessage);
        }

        return $response->json();
    }

    private function getPathForEndpoint($endpoint): string
    {
        if (Str::startsWith($endpoint, '/')) {
            $endpoint = substr($endpoint, 1);
        }
        return self::getHost() . $endpoint;
    }

    private function getCommonHeaders()
    {
        return [
            'Content-Type' => 'application/json',
            'kbn-xsrf' => 'true',
            'Authorization' => 'ApiKey ' . $this->getApiKey(),
        ];
    }

    /**
     * @throws Exception
     */
    private function getApiKey(): string
    {
        $apiKey = config('elastic.kibana.apiKey');
        if (!$apiKey) {
            throw new Exception('No API key provided');
        }
        return $apiKey;
    }

    public static function getHost(): string
    {
        return Str::finish(config('elastic.kibana.host'), '/');
    }
}
