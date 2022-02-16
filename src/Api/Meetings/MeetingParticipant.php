<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingParticipant as MeetingParticipantEntity;

class MeetingParticipant extends AbstractApi
{
    public function list(
        string  $meetingId,
        ?array $additional_data = []
    ): ?array
    {
        $meeting_participants = $this->get('meetingParticipants', [
            'meetingId' => $meetingId,
            'max' => $this->value($additional_data, 'max'),
            'hostEmail' => $this->value($additional_data, 'hostEmail')
        ]);

        if (is_null($meeting_participants)) {
            return null;
        }

        return array_map(function ($meeting_participant) {
            return new MeetingParticipantEntity($meeting_participant);
        }, $meeting_participants->items);
    }
}
