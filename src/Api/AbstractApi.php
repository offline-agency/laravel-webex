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

        return $this->parseResponse($response);
    }

    protected function post($url, $body)
    {
        $url = $this->laravel_webex->base_url . $url;

        $response = $this->laravel_webex->httpBuilder->post($url, $body);

        return $this->parseResponse($response);
    }

    protected function put($url, $body)
    {
        $url = $this->laravel_webex->base_url . $url;

        $response = $this->laravel_webex->httpBuilder->put($url, $body);

        return $this->parseResponse($response);
    }

    protected function delete($url, $query_parameters)
    {
        $url = $this->laravel_webex->base_url . $url;

        $response = $this->laravel_webex->httpBuilder->delete($url, $query_parameters);

        return $this->parseResponse($response);
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
        return $response->status() === 200
            ? $this->successResponse($response)
            : $this->errorResponse($response);
    }

    private function successResponse($response)
    {
        return json_decode($response);
    }

    private function errorResponse($response)
    {
        return null;
    }
}
