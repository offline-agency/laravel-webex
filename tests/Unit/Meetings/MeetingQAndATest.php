<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingQAndA as MeetingQAndAEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingQAndA', function () {
    it('lists Q and A', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/q_and_a*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'q1', 'meetingId' => 'm1', 'text' => 'Question 1'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_q_and_a()->listQAndA('m1');
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingQAndAEntity::class);
    });

    it('lists answers of a question', function () {
        Http::fake([
            'https://webexapis.com/v1/meetings/q_and_a/q1/answers*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'a1', 'questionId' => 'q1', 'text' => 'Answer 1'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->meeting_q_and_a()->listAnswersOfAQuestion('q1', 'm1');
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MeetingQAndAEntity::class);
    });

});
