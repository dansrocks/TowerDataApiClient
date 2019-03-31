<?php

namespace TowerDataApiClient\Services;

use GuzzleHttp\Client;

/**
 * Class AbstracApiService
 *
 * @package TowerDataApiClient\Services
 */
abstract class AbstractApiService
{
    /** @var int */
    protected $timeout = 15;

    /** @var string */
    private $apiKey;

    /** @var Client */
    private $client;

    /** @var string */
    protected $baseUrl;

    /** @var string  */
    protected $endpoint = 'v5/ev';

    /**
     * AbstractApiService constructor.
     *
     * @param string $baseUrl
     * @param string $apiKey
     */
    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * @param mixed[] $params
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function execute($params)
    {
        $client = $this->getClient();
        $response = $client->get(
            $this->endpoint,
            ['query' => $this->buildQueryParams($params)]
        );

        return $response;
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        if (! $this->client) {
            $this->client = $this->buildClient();
        }

        return $this->client;
    }

    /**
     * @return Client
     */
    private function buildClient()
    {
        return new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => $this->timeout,
            'headers'  => [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    private function buildQueryParams(array $params)
    {
        return array_merge($params, [ 'api_key' => $this->apiKey ]);
    }
}