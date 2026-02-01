<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Calling\VoicemailMessage as VoicemailMessageEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Calling VoiceMessaging', function () {
    it('lists voicemail messages', function () {
        Http::fake([
            'https://webexapis.com/v1/voiceMessaging/messages*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'vm1',
                        'userId' => 'u1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->voice_messaging()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VoicemailMessageEntity::class);
        expect($list[0]->id)->toEqual('vm1');
    });

    it('gets voicemail message detail', function () {
        Http::fake([
            'https://webexapis.com/v1/voiceMessaging/messages/vm1*' => Http::response(json_encode((object) [
                'id' => 'vm1',
                'userId' => 'u1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $message = $laravel_webex->voice_messaging()->detail('vm1');

        expect($message)->toBeInstanceOf(VoicemailMessageEntity::class);
        expect($message->id)->toEqual('vm1');
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/voiceMessaging/messages*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->voice_messaging()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
