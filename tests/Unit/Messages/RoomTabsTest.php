<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\RoomTab as RoomTabEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('RoomTabs', function () {
    it('lists room tabs', function () {
        Http::fake([
            'https://webexapis.com/v1/room/tabs*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'tab1',
                        'roomId' => 'room1',
                        'contentUrl' => 'https://example.com',
                        'displayName' => 'Tab One',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->room_tabs()->list('room1');
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(RoomTabEntity::class);
        expect($list[0]->id)->toEqual('tab1');
        expect($list[0]->roomId)->toEqual('room1');
    });

    it('returns error on room tabs list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/room/tabs*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->room_tabs()->list('room1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('creates room tab', function () {
        Http::fake([
            'https://webexapis.com/v1/room/tabs' => Http::response(json_encode((object) [
                'id' => 'tab-new',
                'roomId' => 'room1',
                'contentUrl' => 'https://example.com/tab',
                'displayName' => 'New Tab',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $tab = $laravel_webex->room_tabs()->create('room1', [
            'contentUrl' => 'https://example.com/tab',
            'displayName' => 'New Tab',
        ]);
        assert($tab instanceof RoomTabEntity);

        expect($tab)->toBeInstanceOf(RoomTabEntity::class);
        expect($tab->id)->toEqual('tab-new');
        expect($tab->roomId)->toEqual('room1');
        expect($tab->displayName)->toEqual('New Tab');
    });

    it('gets room tab detail', function () {
        Http::fake([
            'https://webexapis.com/v1/room/tabs/tab1*' => Http::response(json_encode((object) [
                'id' => 'tab1',
                'roomId' => 'room1',
                'contentUrl' => 'https://example.com',
                'displayName' => 'Tab One',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $tab = $laravel_webex->room_tabs()->detail('tab1');
        assert($tab instanceof RoomTabEntity);

        expect($tab)->toBeInstanceOf(RoomTabEntity::class);
        expect($tab->id)->toEqual('tab1');
    });

    it('updates room tab', function () {
        Http::fake([
            'https://webexapis.com/v1/room/tabs/tab1*' => Http::response(json_encode((object) [
                'id' => 'tab1',
                'roomId' => 'room1',
                'contentUrl' => 'https://example.com/new',
                'displayName' => 'Updated Tab',
                'created' => '2024-01-01T00:00:00Z',
                'updated' => '2024-01-02T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $tab = $laravel_webex->room_tabs()->update('tab1', [
            'contentUrl' => 'https://example.com/new',
            'displayName' => 'Updated Tab',
        ]);
        assert($tab instanceof RoomTabEntity);

        expect($tab)->toBeInstanceOf(RoomTabEntity::class);
        expect($tab->displayName)->toEqual('Updated Tab');
    });

    it('destroys room tab', function () {
        Http::fake([
            'https://webexapis.com/v1/room/tabs/tab1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->room_tabs()->destroy('tab1');

        expect($result)->toBeTrue();
    });

    it('returns error on create room tab failure', function () {
        Http::fake([
            'https://webexapis.com/v1/room/tabs*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->room_tabs()->create('room1', [
            'contentUrl' => 'https://example.com/tab',
            'displayName' => 'New Tab',
        ]);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Bad Request');
    });

    it('returns error on room tab detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/room/tabs/tab1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->room_tabs()->detail('tab1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });

    it('returns error on update room tab failure', function () {
        Http::fake([
            'https://webexapis.com/v1/room/tabs/tab1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->room_tabs()->update('tab1', [
            'displayName' => 'Updated Tab',
        ]);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });

    it('returns error on destroy room tab failure', function () {
        Http::fake([
            'https://webexapis.com/v1/room/tabs/tab1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->room_tabs()->destroy('tab1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Forbidden');
    });
});
