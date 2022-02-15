<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingParticipant as MeetingParticipantEntity;

class MeetingParticipant extends AbstractApi
{
    public function list(string $meetingId, ?int $max = null, ?string $hostEmail = null): ?array
    {
        $meeting_participants = $this->get('meetingParticipants', [
            'meetingId' => $meetingId,
            'max' => $max,
            'hostEmail' => $hostEmail
        ]);

        if (is_null($meeting_participants)) {
            return null;
        }

        return array_map(function ($meeting_participant) {
            return new MeetingParticipantEntity($meeting_participant);
        }, $meeting_participants->items);
    }
}
