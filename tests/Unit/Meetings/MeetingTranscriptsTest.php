<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingTranscripts as MeetingTranscriptsEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingTranscripts', function () {
    it('lists transcripts', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 't1', 'meetingId' => 'm1', 'topic' => 'Meeting 1'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_transcripts()->listTranscripts();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingTranscriptsEntity::class);
        expect($list[0]->id)->toEqual('t1');
    });

    it('lists transcripts for compliance officer', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meetingTranscripts*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 't2', 'meetingId' => 'm2', 'topic' => 'Compliance Meeting'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_transcripts()->listTranscriptsForComplianceOfficer('https://example.webex.com');
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingTranscriptsEntity::class);
        expect($list[0]->id)->toEqual('t2');
    });

    it('downloads transcript', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1/download*' => Http::response(json_encode((object) [
                'id' => 't1',
                'downloadUrl' => 'https://example.com/transcript.pdf',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $transcript = $laravel_webex->meeting_transcripts()->downloadTranscript('t1');
        assert($transcript instanceof MeetingTranscriptsEntity);

        expect($transcript)->toBeInstanceOf(MeetingTranscriptsEntity::class);
        expect($transcript->id)->toEqual('t1');
    });

    it('lists snippets of transcript', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1/snippets*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 's1', 'text' => 'Hello world'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_transcripts()->listSnippetsOfTranscript('t1');
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingTranscriptsEntity::class);
    });

    it('gets transcript snippet detail', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1/snippets/s1*' => Http::response(json_encode((object) [
                'id' => 's1',
                'text' => 'Hello world',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $snippet = $laravel_webex->meeting_transcripts()->detailTranscriptSnippet('t1', 's1');
        assert($snippet instanceof MeetingTranscriptsEntity);

        expect($snippet)->toBeInstanceOf(MeetingTranscriptsEntity::class);
        expect($snippet->id)->toEqual('s1');
    });

    it('updates transcript snippet', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingTranscripts/t1/snippets/s1*' => Http::response(json_encode((object) [
                'id' => 's1',
                'text' => 'Updated text',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $snippet = $laravel_webex->meeting_transcripts()->updateTranscriptSnippet('t1', 's1', 'Updated text');
        assert($snippet instanceof MeetingTranscriptsEntity);

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

});
