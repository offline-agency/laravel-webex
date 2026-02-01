<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Event as EventEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Events', function () {
    it('lists events', function () {
        Http::fake([
            'https://webexapis.com/v1/events*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'ev1',
                        'resource' => 'messages',
                        'type' => 'created',
                        'actorId' => 'u1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->events()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(EventEntity::class);
        expect($list[0]->id)->toEqual('ev1');
        expect($list[0]->resource)->toEqual('messages');
        expect($list[0]->type)->toEqual('created');
    });

    it('returns error on events list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/events*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->events()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
