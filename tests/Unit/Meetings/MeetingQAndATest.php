<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingQAndA as MeetingQAndAEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingQAndA', function () {
    it('lists Q and A', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/q_and_a*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'q1', 'meetingId' => 'm1', 'text' => 'Question 1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_q_and_a()->listQAndA('m1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingQAndAEntity::class);
    });

    it('returns error on list Q and A failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/q_and_a*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_q_and_a()->listQAndA('m1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('lists answers of a question', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/q_and_a/q1/answers*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'a1', 'questionId' => 'q1', 'text' => 'Answer 1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_q_and_a()->listAnswersOfAQuestion('q1', 'm1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingQAndAEntity::class);
    });

    it('returns error on list answers of a question failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/q_and_a/q1/answers*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ]), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_q_and_a()->listAnswersOfAQuestion('q1', 'm1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
