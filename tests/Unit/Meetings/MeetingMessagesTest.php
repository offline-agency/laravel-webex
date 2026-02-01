<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingMessages', function () {
    it('destroys meeting message', function () {
        Http::fake([
            'https://webexapis.com/v1/meeting/messages/msg1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->meeting_messages()->destroyMessage('msg1');

        expect($result)->toEqual('Meeting Message deleted');
    });

    it('returns error on destroy meeting message failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meeting/messages/msg1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->meeting_messages()->destroyMessage('msg1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
