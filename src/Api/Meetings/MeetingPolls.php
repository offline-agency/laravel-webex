<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingPolls as MeetingPollsEntity;

class MeetingPolls extends AbstractApi
{
    public function listPolls(
        string $meetingId
    ) {
        $response = $this->get('meetings/polls', [
            'meeting_Id' => $meetingId,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetingPolls = $response->data;

        return array_map(function ($meetingPolls) {
            return new MeetingPollsEntity($meetingPolls);
        }, $meetingPolls->items);
    }

    public function detailPollResults(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max'
        ]);

        $response = $this->get('meetings/pollResults', array_merge([
            'meetingId' => $meetingId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingPollsEntity($response->data);
    }

    public function listRespondentsQuestion(
        string $pollId,
        string $questionId,
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max'
        ]);

        $response = $this->get('meetings/polls/'.$pollId.'/questions/'.$questionId.'/respondents', array_merge([
            'meetingId' => $meetingId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $listRespondentsQuestion = $response->data;

        return array_map(function ($listRespondentsQuestion) {
            return new MeetingPollsEntity($listRespondentsQuestion);
        }, $listRespondentsQuestion->items);
    }
}
