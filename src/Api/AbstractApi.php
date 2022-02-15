<?php

namespace Offlineagency\LaravelWebex\Api;

use Offlineagency\LaravelWebex\LaravelWebex;

abstract class AbstractApi
{
    protected $laravel_webex;

    public function __construct(
        LaravelWebex $laravel_webex
    )
    {
        $this->laravel_webex = $laravel_webex;
    }

    protected function get($url, $query_parameters)
    {
        $url = $this->laravel_webex->base_url . $url;

        $response = $this->laravel_webex->httpBuilder->get($url, $query_parameters);

        return $response->status() === 200
            ? $this->parseResponse($response)
            : $this->parseErrors($response);
    }

    private function parseResponse($response)
    {
        return json_decode($response);
    }

    private function parseErrors($response)
    {
        return null;
    }
}
