<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Events\AuthenticationRequested;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('AbstractApi helpers', function () {
    beforeEach(function () {
        Event::fake([AuthenticationRequested::class, SuccessfulAuthentication::class]);
    });

    it('data() returns only allowed keys from array', function () {
        $api = (new LaravelWebex)->admin_licenses();

        $result = $api->data(['a' => 1, 'b' => 2, 'c' => 3], ['a', 'c']);

        expect($result)->toBe(['a' => 1, 'c' => 3]);
    });

    it('data() omits keys not in fields', function () {
        $api = (new LaravelWebex)->admin_licenses();

        $result = $api->data(['x' => 1, 'y' => 2], ['x']);

        expect($result)->toBe(['x' => 1]);
        expect($result)->not->toHaveKey('y');
    });

    it('data() returns empty array when fields is empty', function () {
        $api = (new LaravelWebex)->admin_licenses();

        $result = $api->data(['a' => 1, 'b' => 2], []);

        expect($result)->toBe([]);
    });

    it('value() returns value when key exists', function () {
        $api = (new LaravelWebex)->admin_licenses();

        $result = $api->value(['x' => 1], 'x');

        expect($result)->toBe(1);
    });

    it('value() returns null when key is missing and no default', function () {
        $api = (new LaravelWebex)->admin_licenses();

        $result = $api->value(['x' => 1], 'y');

        expect($result)->toBeNull();
    });

    it('value() returns default when key is missing', function () {
        $api = (new LaravelWebex)->admin_licenses();

        $result = $api->value([], 'y', 'default');

        expect($result)->toBe('default');
    });

    it('getWithLink returns nextLink null when response has no Link header', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'r1', 'title' => 'Room 1', 'type' => 'group']],
            ]), 200, []),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->listWithPagination();

        expect($result)->toBeArray();
        expect($result)->toHaveKey('nextLink');
        expect($result['nextLink'])->toBeNull();
        expect($result['items'])->toHaveCount(1);
    });

    it('getItemsFromResponse returns empty array when response data has no items', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/availability*' => Http::response(json_encode((object) [])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->video_mesh()->listClusterAvailability('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($list)->toBeArray();
        expect($list)->toHaveCount(0);
    });
});
