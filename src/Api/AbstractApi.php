<?php

namespace Offlineagency\LaravelWebex\Api;

use Illuminate\Support\Arr;
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

    protected function post($url, $body)
    {
        $url = $this->laravel_webex->base_url . $url;

        $response = $this->laravel_webex->httpBuilder->get($url, $body);

        return $response->status() === 200
            ? $this->parseResponse($response)
            : $this->parseErrors($response);
    }

    public function data($data, $fields): array
    {
        $parsed_data = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $parsed_data[$key] = $value;
            }
        }

        return $parsed_data;
    }

    public function value($arr, $key, $default = null)
    {
        return Arr::has($arr, $key)
            ? Arr::get($arr, $key)
            : $default;
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
