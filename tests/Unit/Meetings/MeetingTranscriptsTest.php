<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingTranscripts as MeetingTranscriptsEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingTranscripts', function () {
    it('lists transcripts', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 't1', 'meetingId' => 'm1', 'topic' => 'Meeting 1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_transcripts()->listTranscripts();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingTranscriptsEntity::class);
        expect($list[0]->id)->toEqual('t1');
    });

    it('returns error on list transcripts failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_transcripts()->listTranscripts();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('downloads transcript', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1/download*' => Http::response(json_encode((object) [
                'id' => 't1',
                'downloadUrl' => 'https://example.com/transcript.pdf',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $transcript = $laravel_webex->meeting_transcripts()->downloadTranscript('t1');

        expect($transcript)->toBeInstanceOf(MeetingTranscriptsEntity::class);
        expect($transcript->id)->toEqual('t1');
    });

    it('lists snippets of transcript', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1/snippets*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 's1', 'text' => 'Hello world'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_transcripts()->listSnippetsOfTranscript('t1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingTranscriptsEntity::class);
    });

    it('gets transcript snippet detail', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1/snippets/s1*' => Http::response(json_encode((object) [
                'id' => 's1',
                'text' => 'Hello world',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $snippet = $laravel_webex->meeting_transcripts()->detailTranscriptSnippet('t1', 's1');

        expect($snippet)->toBeInstanceOf(MeetingTranscriptsEntity::class);
        expect($snippet->id)->toEqual('s1');
    });

    it('updates transcript snippet', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1/snippets/s1*' => Http::response(json_encode((object) [
                'id' => 's1',
                'text' => 'Updated text',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $snippet = $laravel_webex->meeting_transcripts()->updateTranscriptSnippet('t1', 's1', 'Updated text');

        expect($snippet)->toBeInstanceOf(MeetingTranscriptsEntity::class);
        expect($snippet->id)->toEqual('s1');
    });

    it('destroys transcript', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_transcripts()->destroyTranscript('t1');

        expect($result)->toEqual('Transcript deleted');
    });

    it('returns error on destroy transcript failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_transcripts()->destroyTranscript('t1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on update transcript snippet failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1/snippets/s1*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_transcripts()->updateTranscriptSnippet('t1', 's1', 'Updated');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
