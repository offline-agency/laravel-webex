<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingPolls as MeetingPollsEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingPolls', function () {
    it('lists polls', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/polls*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'poll1', 'meetingId' => 'm1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->meeting_polls()->listPolls('m1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingPollsEntity::class);
    });

    it('returns error on list polls failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/polls*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->meeting_polls()->listPolls('m1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets poll results detail', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/pollResults*' => Http::response(json_encode((object) [
                'id' => 'poll1',
                'results' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->meeting_polls()->detailPollResults('m1');

        expect($result)->toBeInstanceOf(MeetingPollsEntity::class);
    });

    it('lists respondents for a question', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/polls/poll1/questions/q1/respondents*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'r1', 'personId' => 'p1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->meeting_polls()->listRespondentsQuestion('poll1', 'q1', 'm1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingPollsEntity::class);
    });
});
