<?php

namespace Offlineagency\LaravelWebex\Api;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Offlineagency\LaravelWebex\LaravelWebex;

/**
 * @phpstan-type ApiResponse object{success: bool, data: array<mixed>|object|null}
 * @phpstan-type PaginatedApiResponse object{success: bool, data: array<mixed>|object|null, nextLink: string|null}
 */
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
     * @return ApiResponse
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
     * @param  array<string, mixed>  $query_parameters
     * @return PaginatedApiResponse
     */
    protected function getWithLink(string $url, array $query_parameters): object
    {
        try {
            $fullUrl = $this->laravel_webex->base_url.$url;
            $response = $this->laravel_webex->httpBuilder->get($fullUrl, $query_parameters);
            $parsed = $this->parseResponse($response);

            return (object) [
                'success' => $parsed->success,
                'data' => $parsed->data,
                'nextLink' => $this->parseNextLinkFromResponse($response),
            ];
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
        if (trim($linkHeader) === '') {
            return null;
        }
        if (preg_match('/<([^>]+)>;\s*rel="next"/', $linkHeader, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
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
     * @return ApiResponse
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
     * @return ApiResponse
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
     * @return ApiResponse
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
     * @param  array<string, mixed>|null  $data
     * @param  list<string>  $fields
     * @return array<string, mixed>
     */
    public function data(?array $data, array $fields): array
    {
        $parsed_data = [];
        foreach ($data ?? [] as $key => $value) {
            if (in_array($key, $fields)) {
                $parsed_data[$key] = $value;
            }
        }

        return $parsed_data;
    }

    /**
     * @param  array<string, mixed>|null  $arr
     */
    public function value(?array $arr, string $key, mixed $default = null): mixed
    {
        return Arr::has($arr ?? [], $key)
            ? Arr::get($arr ?? [], $key)
            : $default;
    }

    /**
     * Safely get items array from a list response. Returns empty array when data or items is missing.
     * Items that are not objects are skipped.
     *
     * @param  ApiResponse  $response
     * @return list<object>
     */
    protected function getItemsFromResponse(object $response): array
    {
        if (! is_object($response->data) || ! isset($response->data->items) || ! is_array($response->data->items)) {
            return [];
        }

        return array_values(array_filter($response->data->items, is_object(...)));
    }

    /**
     * Decode the response body. Bodies that do not decode to a JSON object or array yield null data.
     *
     * @return ApiResponse
     */
    private function parseResponse(Response $response): object
    {
        $success = $response->status() === 200 || $response->status() === 204;
        $body = $response->body();
        $data = $body === '' ? null : json_decode($body);

        return (object) [
            'success' => $success,
            'data' => is_array($data) || is_object($data) ? $data : null,
        ];
    }
}
