<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;

class Meeting extends AbstractApi
{
    public function list(
        ?array $additional_data = []
    ): ?array
    {
        $additional_data = $this->data($additional_data, [
            'meetingNumber', 'webLink', 'roomId', 'meetingType', 'state', 'participantEmail', 'current', 'from', 'to', 'max', 'hostEmail', 'siteUrl', 'integrationTag'
        ]);

        $meetings = $this->get('meetings', $additional_data);

        if (is_null($meetings)) {
            return null;
        }

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function detail(
        string $meetingId,
        ?array $additional_data = []
    ): ?MeetingsEntity
    {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail'
        ]);

        $meeting = $this->get('meetings/' . $meetingId, $additional_data);

        if (is_null($meeting)) {
            return null;
        }

        return new MeetingsEntity($meeting);
    }

    public function create(
        string $title,
        string $start,
        string $end,
        ?array $additional_data = []
    ): ?MeetingsEntity
    {
        $additional_data = $this->data($additional_data, [
            'agenda', 'password', 'timezone', 'recurrence', 'enabledAutoRecordMeeting', 'allowAnyUserToBeCoHost', 'enabledJoinBeforeHost', 'enableConnectAudioBeforeHost', 'joinBeforeHostMinutes', 'excludePassword', 'publicMeeting', 'reminderTime', 'sessionTypeId', 'scheduledType', 'enabledWebcastView', 'panelistPassword', 'enableAutomaticLock', 'automaticLockMinutes', 'allowFirstUserToBeCoHost', 'allowAuthenticatedDevices', 'invitees', 'sendEmail', 'hostEmail', 'siteUrl', 'registration', 'integrationTags'
        ]);

        $meeting = $this->post('meetings/', array_merge([
            'title' => $title,
            'start' => $start,
            'end' => $end
        ], $additional_data));

        if (is_null($meeting)) {
            return null;
        }

        return new MeetingsEntity($meeting);
    }
}
