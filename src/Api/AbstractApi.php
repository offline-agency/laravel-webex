<?php

namespace Offlineagency\LaravelWebex\Api;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Offlineagency\LaravelWebex\LaravelWebex;

abstract class AbstractApi
{
    protected LaravelWebex $laravel_webex;

    public function __construct(
        LaravelWebex $laravel_webex
    ) {
        $this->laravel_webex = $laravel_webex;
    }

    /**
     * @param  array<string, mixed>  $query_parameters
     */
    protected function get(string $url, array $query_parameters): object
    {
        try {
            $url = $this->laravel_webex->base_url.$url;
            $response = $this->laravel_webex->httpBuilder->get($url, $query_parameters);

            return $this->parseResponse($response);
        } catch (\Throwable $e) {
            return (object) [
                'success' => false,
                'data' => (object) ['message' => $e->getMessage(), 'errors' => [], 'trackingId' => null],
            ];
        }
    }

    /**
     * Perform a GET request and return parsed response plus next page link from Link header (rel="next").
     * Use for list endpoints that support pagination.
     *
     * @return object{success: bool, data: object|null, nextLink: string|null}
     */
    protected function getWithLink(string $url, array $query_parameters): object
    {
        try {
            $fullUrl = $this->laravel_webex->base_url.$url;
            $response = $this->laravel_webex->httpBuilder->get($fullUrl, $query_parameters);
            $parsed = $this->parseResponse($response);
            $parsed->nextLink = $this->parseNextLinkFromResponse($response);

            return $parsed;
        } catch (\Throwable $e) {
            return (object) [
                'success' => false,
                'data' => (object) ['message' => $e->getMessage(), 'errors' => [], 'trackingId' => null],
                'nextLink' => null,
            ];
        }
    }

    /**
     * Parse the next page URL from a response's Link header (RFC 5988): <url>; rel="next".
     */
    protected function parseNextLinkFromResponse(Response $response): ?string
    {
        $linkHeader = $response->header('Link');
        if (! is_string($linkHeader) || trim($linkHeader) === '') {
            return null;
        }
        if (preg_match('/<([^>]+)>;\s*rel="next"/', $linkHeader, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function post(string $url, array $body): object
    {
        try {
            $url = $this->laravel_webex->base_url.$url;
            $response = $this->laravel_webex->httpBuilder->post($url, $body);

            return $this->parseResponse($response);
        } catch (\Throwable $e) {
            return (object) [
                'success' => false,
                'data' => (object) ['message' => $e->getMessage(), 'errors' => [], 'trackingId' => null],
            ];
        }
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function put(string $url, array $body): object
    {
        try {
            $url = $this->laravel_webex->base_url.$url;
            $response = $this->laravel_webex->httpBuilder->put($url, $body);

            return $this->parseResponse($response);
        } catch (\Throwable $e) {
            return (object) [
                'success' => false,
                'data' => (object) ['message' => $e->getMessage(), 'errors' => [], 'trackingId' => null],
            ];
        }
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function patch(string $url, array $body): object
    {
        try {
            $url = $this->laravel_webex->base_url.$url;
            $response = $this->laravel_webex->httpBuilder->patch($url, $body);

            return $this->parseResponse($response);
        } catch (\Throwable $e) {
            return (object) [
                'success' => false,
                'data' => (object) ['message' => $e->getMessage(), 'errors' => [], 'trackingId' => null],
            ];
        }
    }

    /**
     * @param  array<string, mixed>  $query_parameters
     */
    protected function delete(string $url, array $query_parameters): object
    {
        try {
            $query_string = http_build_query($query_parameters);
            $fullUrl = $this->laravel_webex->base_url.$url.($query_string !== '' ? '?'.$query_string : '');
            $response = $this->laravel_webex->httpBuilder->delete($fullUrl);

            return $this->parseResponse($response);
        } catch (\Throwable $e) {
            return (object) [
                'success' => false,
                'data' => (object) ['message' => $e->getMessage(), 'errors' => [], 'trackingId' => null],
            ];
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $fields
     * @return array<string, mixed>
     */
    public function data(array $data, array $fields): array
    {
        $parsed_data = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $parsed_data[$key] = $value;
            }
        }

        return $parsed_data;
    }

    /**
     * @param  array<string, mixed>  $arr
     */
    public function value(array $arr, string $key, mixed $default = null): mixed
    {
        return Arr::has($arr, $key)
            ? Arr::get($arr, $key)
            : $default;
    }

    /**
     * Safely get items array from a list response. Returns empty array when data or items is missing.
     *
     * @return array<int, object>
     */
    protected function getItemsFromResponse(object $response): array
    {
        if ($response->data === null || ! is_object($response->data)) {
            return [];
        }

        return isset($response->data->items) && is_array($response->data->items)
            ? $response->data->items
            : [];
    }

    private function parseResponse(Response $response): object
    {
        $success = $response->status() === 200 || $response->status() === 204;
        $body = $response->body();
        $data = $body === '' ? null : json_decode($body);

        return (object) [
            'success' => $success,
            'data' => $data,
        ];
    }
}
