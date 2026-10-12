<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Tests\Contract\EndpointHarness;

/*
 * Every public endpoint method of every API class, checked against the official Webex OpenAPI specification
 * (tests/Fixtures/webex-operations.json, built by tools/webex-spec/extract.php from webex/webex-openapi-specs).
 */
dataset('endpoints', function () {
    $endpoints = [];
    foreach (EndpointHarness::endpoints() as $endpoint) {
        // the namespace is part of the key: Admin\\WorkspaceLocations and Devices\\WorkspaceLocations are different APIs
        $endpoints[str_replace('Offlineagency\\LaravelWebex\\Api\\', '', $endpoint['class']).'::'.$endpoint['method']] = [$endpoint['class'], $endpoint['method']];
    }

    return $endpoints;
});

beforeEach(fn () => Sleep::fake());

it('sends only requests that exist in the Webex API', function (string $class, string $method) {
    Http::fake(['*' => Http::response(['items' => [], 'id' => 'x'], 200)]);

    /** @var class-string<AbstractApi> $class */
    $requests = EndpointHarness::requestsOf($class, $method);

    expect($requests)->not->toBeEmpty()
        ->and(array_values(array_diff($requests, EndpointHarness::operations())))->toBe([]);
})->with('endpoints');

it('returns the Webex error of a failed request', function (string $class, string $method) {
    Http::fake(['*' => Http::response(['message' => 'Not found', 'errors' => [], 'trackingId' => 'T-404'], 404)]);

    /** @var class-string<AbstractApi> $class */
    $result = EndpointHarness::call($class, $method);

    if ($result instanceof Error) {
        expect($result->message)->toBe('Not found')
            ->and($result->trackingId)->toBe('T-404')
            ->and($result->status)->toBe(404);
    } else {
        // the few methods documented to return the raw response object
        expect($result)->toBeObject()
            ->and(is_object($result) && property_exists($result, 'success') ? $result->success : null)->toBeFalse();
    }
})->with('endpoints');
