<?php

namespace Offlineagency\LaravelWebex\Api;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use JsonException;
use Offlineagency\LaravelWebex\Entities\AbstractEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

/**
 * @phpstan-type ApiResponse object{success: bool, status: int, data: array<mixed>|object|null}
 * @phpstan-type PaginatedApiResponse object{success: bool, status: int, data: array<mixed>|object|null, nextLink: string|null}
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
        } catch (ConnectionException $e) {
            return $this->failure('Connection to Webex failed: '.$e->getMessage());
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
                'status' => $parsed->status,
                'data' => $parsed->data,
                'nextLink' => $this->parseNextLinkFromResponse($response),
            ];
        } catch (ConnectionException $e) {
            $failure = $this->failure('Connection to Webex failed: '.$e->getMessage());

            return (object) ['success' => false, 'status' => 0, 'data' => $failure->data, 'nextLink' => null];
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
        } catch (ConnectionException $e) {
            return $this->failure('Connection to Webex failed: '.$e->getMessage());
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
        } catch (ConnectionException $e) {
            return $this->failure('Connection to Webex failed: '.$e->getMessage());
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
        } catch (ConnectionException $e) {
            return $this->failure('Connection to Webex failed: '.$e->getMessage());
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
        } catch (ConnectionException $e) {
            return $this->failure('Connection to Webex failed: '.$e->getMessage());
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
     * The entity of a successful response, or the Error of a failed one. A successful response without a JSON
     * document (empty body, JSON null) is an Error too: no entity is built from nothing.
     *
     * @template T of AbstractEntity
     *
     * @param  ApiResponse  $response
     * @param  class-string<T>  $class
     * @return T|Error
     */
    protected function entity(object $response, string $class): AbstractEntity
    {
        if (! $response->success) {
            return new Error($response->data);
        }
        if ($response->data === null) {
            return new Error($this->failure("Webex returned no document (HTTP {$response->status}).", $response->status)->data);
        }

        return new $class($response->data);
    }

    /**
     * Decode a response. Any 2xx status is a success; its body must be empty or a JSON object/array (a malformed
     * body or a JSON scalar is reported, never turned into an empty result). A failed response keeps the Webex
     * error document when the body is one, and never echoes another body (proxies may include request details).
     *
     * @return ApiResponse
     */
    private function parseResponse(Response $response): object
    {
        $status = $response->status();
        $body = $response->body();
        $decoded = null;
        $malformed = false;
        if (trim($body) !== '') {
            try {
                $decoded = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                $malformed = true;
            }
        }

        if (! $response->successful()) {
            return is_object($decoded) && property_exists($decoded, 'message')
                ? (object) ['success' => false, 'status' => $status, 'data' => $decoded]
                : $this->failure("Webex request failed (HTTP {$status}).", $status);
        }
        if ($malformed) {
            return $this->failure("Webex returned a malformed JSON response (HTTP {$status}).", $status);
        }
        if ($decoded !== null && ! is_array($decoded) && ! is_object($decoded)) {
            return $this->failure("Webex returned an unexpected JSON value (HTTP {$status}).", $status);
        }

        return (object) ['success' => true, 'status' => $status, 'data' => $decoded];
    }

    /**
     * @return ApiResponse
     */
    private function failure(string $message, int $status = 0): object
    {
        return (object) [
            'success' => false,
            'status' => $status,
            'data' => (object) ['message' => $message, 'errors' => [], 'trackingId' => null],
        ];
    }
}
