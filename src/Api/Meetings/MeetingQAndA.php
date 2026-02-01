<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingQAndA as MeetingQAndAEntity;

class MeetingQAndA extends AbstractApi
{
    public function listQAndA(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max'
        ]);

        $response = $this->get('meetings/q_and_a', array_merge([
            'meeting_Id' => $meetingId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetingQAndA = $response->data;

        return array_map(function ($meetingQAndA) {
            return new MeetingQAndAEntity($meetingQAndA);
        }, $meetingQAndA->items);
    }

    public function listAnswersOfAQuestion(
        string $questionId,
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max'
        ]);

        $response = $this->get('meetings/q_and_a/'.$questionId.'/answers', array_merge([
            'meetingId' => $meetingId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetingQAndA = $response->data;

        return array_map(function ($meetingQAndA) {
            return new MeetingQAndAEntity($meetingQAndA);
        }, $meetingQAndA->items);
    }
}
