<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;

class Meeting extends AbstractApi
{
    public function list(
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'meetingNumber', 'webLink', 'roomId', 'meetingType', 'state', 'participantEmail', 'current', 'from', 'to', 'max', 'hostEmail', 'siteUrl', 'integrationTag'
        ]);

        $response = $this->get('meetings', $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        $meetings = $response->data;

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function detail(
        string $meetingId,
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'current', 'hostEmail'
        ]);

        $response = $this->get('meetings/' . $meetingId, $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function create(
        string $title,
        string $start,
        string $end,
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'agenda', 'password', 'timezone', 'recurrence', 'enabledAutoRecordMeeting', 'allowAnyUserToBeCoHost', 'enabledJoinBeforeHost', 'enableConnectAudioBeforeHost', 'joinBeforeHostMinutes', 'excludePassword', 'publicMeeting', 'reminderTime', 'sessionTypeId', 'scheduledType', 'enabledWebcastView', 'panelistPassword', 'enableAutomaticLock', 'automaticLockMinutes', 'allowFirstUserToBeCoHost', 'allowAuthenticatedDevices', 'invitees', 'sendEmail', 'hostEmail', 'siteUrl', 'registration', 'integrationTags'
        ]);

        $response = $this->post('meetings', array_merge([
            'title' => $title,
            'start' => $start,
            'end' => $end
        ], $additional_data));

        if (!$response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function update(
        string $meeting_id,
        string $title,
        string $password,
        string $start,
        string $end,
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'agenda', 'timezone', 'recurrence', 'enabledAutoRecordMeeting', 'allowAnyUserToBeCoHost', 'enabledJoinBeforeHost', 'enableConnectAudioBeforeHost', 'joinBeforeHostMinutes', 'excludePassword', 'publicMeeting', 'reminderTime', 'sessionTypeId', 'scheduledType', 'enabledWebcastView', 'panelistPassword', 'enableAutomaticLock', 'automaticLockMinutes', 'allowFirstUserToBeCoHost', 'allowAuthenticatedDevices', 'sendEmail', 'hostEmail', 'siteUrl', 'registration', 'integrationTags'
        ]);

        $response = $this->put('meetings/' . $meeting_id, array_merge([
            'title' => $title,
            'password' => $password,
            'start' => $start,
            'end' => $end
        ], $additional_data));

        if (!$response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function destroy(
        string $meeting_id,
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'sendEmail'
        ]);

        $response = $this->delete('meetings/' . $meeting_id, $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        return 'Meeting deleted';
    }
}
