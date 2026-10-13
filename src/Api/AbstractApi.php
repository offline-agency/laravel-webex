<?php

namespace Offlineagency\LaravelWebex\Api;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\LazyCollection;
use JsonException;
use Offlineagency\LaravelWebex\Entities\AbstractEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Exceptions\WebexPaginationException;
use Offlineagency\LaravelWebex\Exceptions\WebexRequestException;
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
            $url = $this->laravel_webex->base_url.rtrim($url, '/');
            $response = $this->laravel_webex->http('GET')->get($url, $query_parameters);

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
        return $this->getAbsoluteWithLink($this->laravel_webex->base_url.rtrim($url, '/'), $query_parameters);
    }

    /**
     * Every item of a paginated list, fetched page by page while the collection is consumed: the next page is the
     * Link rel="next" URL, followed only on the scheme, host and port of the configured base URL (the bearer token
     * is never sent elsewhere). A failed page throws WebexRequestException; a repeated nextLink or more than
     * $maxPages pages throw WebexPaginationException, so an incomplete traversal is never silent.
     *
     * @template T of AbstractEntity
     *
     * @param  array<string, mixed>  $query_parameters
     * @param  class-string<T>  $class
     * @return LazyCollection<int, T>
     */
    protected function cursorOf(string $url, array $query_parameters, string $class, int $maxPages): LazyCollection
    {
        return LazyCollection::make(function () use ($url, $query_parameters, $class, $maxPages) {
            $next = $this->laravel_webex->base_url.rtrim($url, '/');
            $query = $query_parameters;
            $seen = [];
            for ($pages = 0; $next !== null; $pages++) {
                if ($pages === $maxPages) {
                    throw new WebexPaginationException("Stopped after {$maxPages} pages (maxPages).");
                }
                $response = $this->getAbsoluteWithLink($next, $query);
                if (! $response->success) {
                    throw new WebexRequestException(new Error($response->data));
                }
                foreach ($this->getItemsFromResponse($response) as $item) {
                    yield new $class($item);
                }

                $next = $response->nextLink;
                $query = [];
                if ($next !== null && ! $this->sameOriginAsBaseUrl($next)) {
                    throw new WebexPaginationException('Refusing to follow a nextLink outside '.$this->origin($this->laravel_webex->base_url).'.');
                }
                if ($next !== null && isset($seen[$next])) {
                    throw new WebexPaginationException('Webex returned the same nextLink twice.');
                }
                $seen[(string) $next] = true;
            }
        });
    }

    private function sameOriginAsBaseUrl(string $url): bool
    {
        $origin = $this->origin($url);

        return $origin !== '' && $origin === $this->origin($this->laravel_webex->base_url);
    }

    /**
     * scheme://host[:port] of an https URL, lower-cased; '' for anything else.
     */
    private function origin(string $url): string
    {
        $parts = parse_url($url);
        if (! is_array($parts) || strtolower($parts['scheme'] ?? '') !== 'https' || ! isset($parts['host'])) {
            return '';
        }

        return 'https://'.strtolower($parts['host']).(isset($parts['port']) ? ':'.$parts['port'] : '');
    }

    /**
     * @param  array<string, mixed>  $query_parameters
     * @return PaginatedApiResponse
     */
    private function getAbsoluteWithLink(string $fullUrl, array $query_parameters): object
    {
        try {
            // an empty query array would replace the query string of $fullUrl (a nextLink carries its own)
            $response = $this->laravel_webex->http('GET')->get($fullUrl, $query_parameters === [] ? null : $query_parameters);
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
            $url = $this->laravel_webex->base_url.rtrim($url, '/');
            $response = $this->laravel_webex->http('POST')->post($url, $body);

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
            $url = $this->laravel_webex->base_url.rtrim($url, '/');
            $response = $this->laravel_webex->http('PUT')->put($url, $body);

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
            $url = $this->laravel_webex->base_url.rtrim($url, '/');
            $response = $this->laravel_webex->http('PATCH')->patch($url, $body);

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
            $fullUrl = $this->laravel_webex->base_url.rtrim($url, '/').($query_string !== '' ? '?'.$query_string : '');
            $response = $this->laravel_webex->http('DELETE')->delete($fullUrl);

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
            $document = is_object($decoded) && property_exists($decoded, 'message')
                ? get_object_vars($decoded)
                : ['message' => "Webex request failed (HTTP {$status}).", 'errors' => [], 'trackingId' => null];
            $document['status'] = $status;
            $document['retryAfter'] = LaravelWebex::retryAfterSeconds($response);

            return (object) ['success' => false, 'status' => $status, 'data' => (object) $document];
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
