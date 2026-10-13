<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingMessages', function () {
    it('destroys meeting message', function () {
        Http::fake([
            'https://webexapis.com/v1/meeting/messages/msg1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_messages()->destroyMessage('msg1');

        expect($result)->toEqual('Meeting Message deleted');
    });

});
