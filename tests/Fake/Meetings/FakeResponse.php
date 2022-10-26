<?php

namespace Offlineagency\LaravelWebex\Tests\Fake\Meetings;

use Offlineagency\LaravelWebex\Tests\Fake\BaseFakeResponse;

class FakeResponse extends BaseFakeResponse
{
    public function fakeMeeting(array $params = []): object
    {
        return (object) [
            'id' => $this->value($params, 'id', 'fake_id'),
            'meetingNumber' => $this->value($params, 'id', 'fake_meetingNumber'),
            'title' => $this->value($params, 'id', 'fake_title'),
            'password' => $this->value($params, 'id', 'fake_password'),
            'phoneAndVideoSystemPassword' => $this->value($params, 'id', 'fake_phoneAndVideoSystemPassword'),
            'meetingType' => $this->value($params, 'id', 'fake_meetingType'),
            'state' => $this->value($params, 'id', 'fake_state'),
            'timezone' => $this->value($params, 'id', 'fake_timezone'),
            'start' => $this->value($params, 'id', 'fake_start'),
            'end' => $this->value($params, 'id', 'fake_end'),
            'hostUserId' => $this->value($params, 'id', 'fake_hostUserId'),
            'hostDisplayName' => $this->value($params, 'id', 'fake_hostDisplayName'),
            'hostEmail' => $this->value($params, 'id', 'fake_hostEmail'),
            'hostKey' => $this->value($params, 'id', 'fake_hostKey'),
            'siteUrl' => $this->value($params, 'id', 'fake_siteUrl'),
            'webLink' => $this->value($params, 'id', 'fake_webLink'),
            'sipAddress' => $this->value($params, 'id', 'fake_sipAddress'),
            'dialInIpAddress' => $this->value($params, 'dialInIpAddress', 'fake_dialInIpAddress'),
            'enabledAutoRecordMeeting' => $this->value($params, 'enabledAutoRecordMeeting', false),
            'allowAuthenticatedDevices' => $this->value($params, 'allowAuthenticatedDevices', false),
            'enabledJoinBeforeHost' => $this->value($params, 'enabledJoinBeforeHost', true),
            'joinBeforeHostMinutes' => $this->value($params, 'joinBeforeHostMinutes', 5),
            'enableConnectAudioBeforeHost' => $this->value($params, 'enableConnectAudioBeforeHost', true),
            'excludePassword' => $this->value($params, 'excludePassword', false),
            'publicMeeting' => $this->value($params, 'publicMeeting', false),
            'reminderTime' => $this->value($params, 'reminderTime', 15),
            'enableAutomaticLock' => $this->value($params, 'enableAutomaticLock', false),
            'sessionTypeId' => $this->value($params, 'sessionTypeId', 600),
            'scheduledType' => $this->value($params, 'scheduledType', 'fake_scheduledType'),
            'agenda' => $this->value($params, 'agenda', 'fake_agenda'),
            'recurrence' => $this->value($params, 'recurrence', 'fake_recurrence'),
            'roomId' => $this->value($params, 'roomId', 'fake_roomId'),
            'allowAnyUserToBeCoHost' => $this->value($params, 'allowAnyUserToBeCoHost', false),
            'enabledWebcastView' => $this->value($params, 'enabledWebcastView', false),
            'panelistPassword' => $this->value($params, 'panelistPassword', 'fake_panelistPassword'),
            'phoneAndVideoSystemPanelistPassword' => $this->value($params, 'phoneAndVideoSystemPanelistPassword', 'fake_phoneAndVideoSystemPanelistPassword'),
            'automaticLockMinutes' => $this->value($params, 'automaticLockMinutes', 10),
            'allowFirstUserToBeCoHost' => $this->value($params, 'allowFirstUserToBeCoHost', false),
            'telephony' => $this->value($params, 'telephony', (object) []),
            'registration' => $this->value($params, 'registration', (object) []),
            'integrationTags' => $this->value($params, 'integrationTags', []),
        ];
    }

    public function fakeMeetingParticipant(array $params = []): object
    {
        return (object) [
            'id' => $this->value($params, 'id', 'fake_id'),
            'orgId' => $this->value($params, 'orgId', 'fake_orgId'),
            'host' => $this->value($params, 'host', false),
            'coHost' => $this->value($params, 'coHost', false),
            'spaceModerator' => $this->value($params, 'spaceModerator', false),
            'email' => $this->value($params, 'email', 'fake_email'),
            'displayName' => $this->value($params, 'displayName', 'fake_displayName'),
            'invitee' => $this->value($params, 'invitee', true),
            'video' => $this->value($params, 'video', 'fake_video'),
            'muted' => $this->value($params, 'muted', false),
            'state' => $this->value($params, 'state', 'fake_state'),
            'siteUrl' => $this->value($params, 'siteUrl', 'fake_siteUrl'),
            'meetingId' => $this->value($params, 'meetingId', 'fake_meetingId'),
            'hostEmail' => $this->value($params, 'hostEmail', 'fake_hostEmail'),
            'devices' => $this->value($params, 'devices', [
                'deviceType' => 'fake_deviceType',
                'audioType' => 'fake_audioType',
                'joinedTime' => 'fake_joinedTime',
                'leftTime' => 'fake_leftTime',
                'correlationId' => 'fake_correlationId',
            ]),
        ];
    }

    public function fakeMeetingInvitee(array $params = []): object
    {
        return (object) [
            'id' => $this->value($params, 'id', 'fake_id'),
            'email' => $this->value($params, 'email', 'fake_email'),
            'displayName' => $this->value($params, 'displayName', 'fake_displayName'),
            'coHost' => $this->value($params, 'coHost', false),
            'meetingId' => $this->value($params, 'meetingId', 'fake_meetingId'),
            'panelist' => $this->value($params, 'panelist', false),
        ];
    }

    public function fakeError(array $params = []): object
    {
        return (object) [
            'message' => $this->value($params, 'id', 'fake_message'),
            'errors' => [
                (object) [
                    $this->value($params, 'id', 'fake_error'),
                ],
            ],
            'trackingId' => $this->value($params, 'id', 'fake_trackingId'),
        ];
    }
}
