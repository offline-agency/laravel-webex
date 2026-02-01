<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Room as RoomEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Rooms', function () {
    it('lists rooms', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'r1', 'title' => 'Room 1', 'type' => 'group'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->rooms()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(RoomEntity::class);
        expect($list[0]->id)->toEqual('r1');
        expect($list[0]->title)->toEqual('Room 1');
    });

    it('returns error on rooms list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('creates room', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms*' => Http::response(json_encode((object) [
                'id' => 'r2',
                'title' => 'New Room',
                'type' => 'group',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $room = $laravel_webex->rooms()->create('New Room');

        expect($room)->toBeInstanceOf(RoomEntity::class);
        expect($room->id)->toEqual('r2');
        expect($room->title)->toEqual('New Room');
    });

    it('gets room detail', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms/r1*' => Http::response(json_encode((object) [
                'id' => 'r1',
                'title' => 'Room 1',
                'type' => 'group',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $room = $laravel_webex->rooms()->detail('r1');

        expect($room)->toBeInstanceOf(RoomEntity::class);
        expect($room->id)->toEqual('r1');
    });

    it('updates room', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms/r1*' => Http::response(json_encode((object) [
                'id' => 'r1',
                'title' => 'Updated Room',
                'type' => 'group',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $room = $laravel_webex->rooms()->update('r1', ['title' => 'Updated Room']);

        expect($room)->toBeInstanceOf(RoomEntity::class);
        expect($room->title)->toEqual('Updated Room');
    });

    it('destroys room', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms/r1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->destroy('r1');

        expect($result)->toBeTrue();
    });

    it('returns error on destroy room failure', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms/r1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->destroy('r1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets room meeting details', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms/r1/meetingInfo*' => Http::response(json_encode((object) [
                'meetingLink' => 'https://example.webex.com/meet/r1',
                'sipAddress' => 'r1@example.webex.com',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->meetingDetails('r1');

        expect($result)->toBeObject();
        expect($result->meetingLink)->toEqual('https://example.webex.com/meet/r1');
    });

    it('lists rooms with pagination and returns next link', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms*' => Http::response(
                json_encode((object) [
                    'items' => [
                        (object) ['id' => 'r1', 'title' => 'Room 1', 'type' => 'group'],
                    ],
                ]),
                200,
                ['Link' => '<https://webexapis.com/v1/rooms?max=2&before=xyz>; rel="next"']
            ),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->listWithPagination(['max' => 2]);

        expect($result)->toBeArray();
        expect($result)->toHaveKey('items');
        expect($result)->toHaveKey('nextLink');
        expect($result['items'])->toHaveCount(1);
        expect($result['nextLink'])->toEqual('https://webexapis.com/v1/rooms?max=2&before=xyz');
    });

    it('returns error on create room failure', function () {
        Http::fake([
            'https://webexapis.com/v1/rooms*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->rooms()->create('New Room');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
