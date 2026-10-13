<?php

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Room as RoomEntity;
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

    it('data() treats null data as empty', function () {
        $api = (new LaravelWebex)->admin_licenses();

        expect($api->data(null, ['a']))->toBe([]);
    });

    it('value() treats null array as empty', function () {
        $api = (new LaravelWebex)->admin_licenses();

        expect($api->value(null, 'x', 'default'))->toBe('default');
    });

    it('getWithLink returns nextLink null when response has no Link header', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'r1', 'title' => 'Room 1', 'type' => 'group']],
            ], JSON_THROW_ON_ERROR), 200, []),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->listWithPagination();
        assert(is_array($result));

        expect($result)->toBeArray();
        expect($result)->toHaveKey('nextLink');
        expect($result['nextLink'])->toBeNull();
        expect($result['items'])->toHaveCount(1);
    });

    it('getItemsFromResponse returns empty array when response data has no items', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/availability*' => Http::response(json_encode((object) [], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->video_mesh()->listClusterAvailability('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($list)->toBeArray();
        expect($list)->toHaveCount(0);
    });

    it('getItemsFromResponse returns empty array when response body is empty (data null)', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms*' => Http::response('', 200),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->rooms()->list();

        expect($list)->toBeArray();
        expect($list)->toHaveCount(0);
    });

    it('returns error when get() throws', function () {
        Http::fake([
            '*' => fn () => throw new ConnectionException('Connection refused'),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_workspaces()->list();
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Connection to Webex failed: Connection refused');
    });

    it('returns error when getWithLink() throws', function () {
        Http::fake([
            '*' => fn () => throw new ConnectionException('Connection refused'),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->listWithPagination();
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Connection to Webex failed: Connection refused');
    });

    it('returns error when post() throws', function () {
        Http::fake([
            '*' => fn () => throw new ConnectionException('Connection refused'),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_workspaces()->create([
            'name' => 'Test',
            'orgId' => 'org1',
            'locationId' => 'loc1',
        ]);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Connection to Webex failed: Connection refused');
    });

    it('returns error when put() throws', function () {
        Http::fake([
            '*' => fn () => throw new ConnectionException('Connection refused'),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_workspaces()->update('ws1', ['name' => 'Updated']);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Connection to Webex failed: Connection refused');
    });

    it('returns error when patch() throws', function () {
        Http::fake([
            '*' => fn () => throw new ConnectionException('Connection refused'),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_configurations()->update(['deviceId' => 'dev1']);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Connection to Webex failed: Connection refused');
    });

    it('returns error when delete() throws', function () {
        Http::fake([
            '*' => fn () => throw new ConnectionException('Connection refused'),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_workspaces()->destroy('ws1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Connection to Webex failed: Connection refused');
    });

    it('delete() with non-empty query params builds URL and succeeds', function () {
        Http::fake([
            'https://webexapis.com/v1/recordings/rec1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->recordings()->destroyRecording('rec1', ['reason' => 'test', 'comment' => 'done']);

        expect($result)->toEqual('Recording deleted');
    });

    it('getWithLink returns nextLink null when Link header has no rel="next"', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'r1', 'title' => 'Room 1', 'type' => 'group']],
            ], JSON_THROW_ON_ERROR), 200, ['Link' => '<https://webexapis.com/v1/rooms?before=abc>; rel="prev"']),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->listWithPagination();
        assert(is_array($result));

        expect($result)->toHaveKey('nextLink');
        expect($result['nextLink'])->toBeNull();
        expect($result['items'])->toHaveCount(1);
    });

    it('returns an error instead of an empty entity when a successful response body is a JSON scalar', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms/r1*' => Http::response(json_encode('unexpected', JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $room = $laravel_webex->rooms()->detail('r1');
        assert($room instanceof Error);

        expect($room->message)->toEqual('Webex returned an unexpected JSON value (HTTP 200).');
    });

    it('returns a generic error when a failed response body is a JSON scalar', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms/r1*' => Http::response(json_encode(42, JSON_THROW_ON_ERROR), 500),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->detail('r1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Webex request failed (HTTP 500).');
    });

    it('reports raw responses whose body is a JSON scalar as unsuccessful', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/answer*' => Http::response(json_encode(true, JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->answer(['callId' => 'c1']);

        expect($result->success)->toBeFalse();
        expect($result->data->message ?? null)->toEqual('Webex returned an unexpected JSON value (HTTP 200).');
    });

    it('getItemsFromResponse skips items that are not objects', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'r1'], 'oops', 42, [1, 2], null],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->rooms()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(RoomEntity::class);
        expect($list[0]->id)->toEqual('r1');
    });
});
