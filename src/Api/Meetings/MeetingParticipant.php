<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingParticipant as MeetingParticipantEntity;

class MeetingParticipant extends AbstractApi
{
    public function list(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $response = $this->get('meetingParticipants', [
            'meetingId' => $meetingId,
            'max' => $this->value($additional_data, 'max'),
            'hostEmail' => $this->value($additional_data, 'hostEmail'),
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meeting_participants = $response->data;

        return array_map(function ($meeting_participant) {
            return new MeetingParticipantEntity($meeting_participant);
        }, $meeting_participants->items);
    }

    public function queryWIthEmail(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'meetingStartTimeFrom', 'meetingStartTimeTo', 'hostEmail', 'emails', 'joinTimeFrom', 'joinTimeTo'
        ]);

        $response = $this->post('meetingParticipants/query', array_merge([
            'meetingId' => $meetingId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingParticipantEntity($response->data);
    }

    public function detail(
        string $meetingParticipantId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail',
        ]);

        $response = $this->get('meetingParticipants/'.$meetingParticipantId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingParticipantEntity($response->data);
    }

    public function update(
        string $participantId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'muted', 'admit', 'expel'
        ]);

        $response = $this->post('meetingParticipants/' . $participantId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingParticipantEntity($response->data);
    }

    public function admit(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'items' => [
                'participantId'
            ]
        ]);

        $response = $this->post('meetingParticipants/admit', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingParticipantEntity($response->data);
    }
}
