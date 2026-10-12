<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingClosedCaptions as MeetingClosedCaptionsEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingClosedCaptions', function () {
    it('lists closed captions', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingClosedCaptions*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'cc1', 'meetingId' => 'm1'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_closed_captions()->listClosedCaptions('m1');
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingClosedCaptionsEntity::class);
    });

    it('returns error on list closed captions failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingClosedCaptions*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_closed_captions()->listClosedCaptions('m1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('lists closed caption snippets', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingClosedCaptions/cc1/snippets*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 's1', 'text' => 'Hello'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_closed_captions()->listClosedCaptionSnippets('cc1', 'm1');
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingClosedCaptionsEntity::class);
    });

    it('downloads closed caption snippets', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingClosedCaptions/cc1/download*' => Http::response(json_encode((object) [
                'id' => 'cc1',
                'downloadUrl' => 'https://example.com/cc.vtt',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_closed_captions()->downloadClosedCaptionSnippets('cc1', 'm1');

        expect($result)->toBeInstanceOf(MeetingClosedCaptionsEntity::class);
    });

    it('returns error on list closed caption snippets failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingClosedCaptions/cc1/snippets*' => Http::response(json_encode((object) [
                'message' => 'fake_message',
                'errors' => [],
                'trackingId' => 'fake_trackingId',
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_closed_captions()->listClosedCaptionSnippets('cc1', 'm1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('fake_message');
        expect($result->errors)->toBeArray();
        expect($result->trackingId)->toEqual('fake_trackingId');
    });

    it('returns error on download closed caption snippets failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingClosedCaptions/cc1/download*' => Http::response(json_encode((object) [
                'message' => 'fake_message',
                'errors' => [],
                'trackingId' => 'fake_trackingId',
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_closed_captions()->downloadClosedCaptionSnippets('cc1', 'm1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('fake_message');
        expect($result->errors)->toBeArray();
        expect($result->trackingId)->toEqual('fake_trackingId');
    });
});
