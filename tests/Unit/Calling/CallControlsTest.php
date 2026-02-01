<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Calling\Call as CallEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('CallControls', function () {
    it('dials and returns call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/dial*' => Http::response(json_encode((object) [
                'callId' => 'call1',
                'callSessionId' => 'session1',
                'state' => 'initiated',
                'direction' => 'outbound',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->call_controls()->dial([
            'callbackNumber' => '+15551234567',
            'destination' => '+15559876543',
        ]);

        expect($result)->toBeInstanceOf(CallEntity::class);
        expect($result->callId)->toEqual('call1');
    });

    it('lists calls', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'callId' => 'call1',
                        'callSessionId' => 'session1',
                        'state' => 'connected',
                        'direction' => 'inbound',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->call_controls()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(CallEntity::class);
        expect($list[0]->callId)->toEqual('call1');
    });

    it('gets call detail', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/call1*' => Http::response(json_encode((object) [
                'callId' => 'call1',
                'callSessionId' => 'session1',
                'state' => 'connected',
                'direction' => 'inbound',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $call = $laravel_webex->call_controls()->detail('call1');

        expect($call)->toBeInstanceOf(CallEntity::class);
        expect($call->callId)->toEqual('call1');
    });

    it('returns error on dial failure', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/dial*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->call_controls()->dial([]);

        expect($result)->toBeInstanceOf(Error::class);
    });
});
