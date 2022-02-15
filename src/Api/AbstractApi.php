<?php

namespace Offlineagency\LaravelWebex\Api;

use Offlineagency\LaravelWebex\Client;

abstract class AbstractApi
{
    protected $client;

    public function __construct(
        Client $client
    )
    {
        $this->client = $client;
    }

    protected function get($url, $query_parameters)
    {
        $url = $this->client->base_url . $url;

        $response = $this->client->httpBuilder->get($url, $query_parameters);

        return $response->status() === 200
            ? $this->parseResponse($response)
            : null;
    }

    private function parseResponse($response)
    {
        return json_decode($response);
    }
}
