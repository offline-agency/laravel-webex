<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingChats as MeetingChatsEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingChats', function () {
    it('lists meeting chats', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/postMeetingChats*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'c1', 'meetingId' => 'm1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->meeting_chats()->listChats('m1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingChatsEntity::class);
    });

    it('returns error on list meeting chats failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/postMeetingChats*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->meeting_chats()->listChats('m1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('destroys meeting chats', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/postMeetingChats/m1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->meeting_chats()->destroyChats('m1');

        expect($result)->toEqual('Meeting chats deleted');
    });

    it('returns error on destroy meeting chats failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/postMeetingChats/m1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->meeting_chats()->destroyChats('m1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
