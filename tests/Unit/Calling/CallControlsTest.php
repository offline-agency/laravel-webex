<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Calling\Call as CallEntity;
use Offlineagency\LaravelWebex\Entities\Calling\CallHistoryItem as CallHistoryItemEntity;
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

        $laravel_webex = new LaravelWebex;
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

        $laravel_webex = new LaravelWebex;
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

        $laravel_webex = new LaravelWebex;
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

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->dial([]);

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on list calls failure', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on call detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/call1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ]), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->detail('call1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('answers call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/answer*' => Http::response(json_encode((object) [
                'callId' => 'call1',
                'state' => 'connected',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->answer(['callId' => 'call1']);

        expect($result)->toBeObject();
        expect($result->success)->toBeTrue();
    });

    it('rejects call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/reject*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->reject(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('hangs up call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/hangup*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->hangup(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('holds call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/hold*' => Http::response(json_encode((object) ['callId' => 'call1', 'state' => 'held'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->hold(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('resumes call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/resume*' => Http::response(json_encode((object) ['callId' => 'call1', 'state' => 'connected'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->resume(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('mutes call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/mute*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->mute(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('unmutes call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/unmute*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->unmute(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('diverts call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/divert*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->divert(['callId' => 'call1', 'destination' => '+15551234567']);

        expect($result->success)->toBeTrue();
    });

    it('transfers call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/transfer*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->transfer(['callId' => 'call1', 'transferTo' => '+15551234567']);

        expect($result->success)->toBeTrue();
    });

    it('parks call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/park*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->park(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('retrieves call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/retrieve*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->retrieve(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('starts recording', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/startRecording*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->startRecording(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('stops recording', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/stopRecording*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->stopRecording(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('pauses recording', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/pauseRecording*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->pauseRecording(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('resumes recording', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/resumeRecording*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->resumeRecording(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('transmits dtmf', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/transmitDtmf*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->transmitDtmf(['callId' => 'call1', 'dtmf' => '1']);

        expect($result->success)->toBeTrue();
    });

    it('pushes call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/push*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->push(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('picks up call', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/pickup*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->pickup(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('barges in', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/bargeIn*' => Http::response(json_encode((object) ['callId' => 'call1'])),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->bargeIn(['callId' => 'call1']);

        expect($result->success)->toBeTrue();
    });

    it('lists call history', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/history*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'h1',
                        'callId' => 'call1',
                        'direction' => 'outbound',
                        'startTime' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->call_controls()->history();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(CallHistoryItemEntity::class);
    });

    it('returns error on list call history failure', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/history*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->history();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on answer failure', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/calls/answer*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->call_controls()->answer(['callId' => 'call1']);

        expect($result->success)->toBeFalse();
    });
});
