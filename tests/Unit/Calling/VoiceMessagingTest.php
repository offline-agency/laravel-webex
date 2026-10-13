<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Calling\VoicemailMessage as VoicemailMessageEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Calling VoiceMessaging', function () {
    it('lists voicemail messages', function () {
        Http::fake([
            'https://webexapis.com/v1/telephony/voiceMessages*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'vm1',
                        'userId' => 'u1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->voice_messaging()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VoicemailMessageEntity::class);
        expect($list[0]->id)->toEqual('vm1');
    });

});
