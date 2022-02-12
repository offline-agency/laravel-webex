<?php

namespace Offlineagency\LaravelWebex\Tests\Fake;

class MeetingsFakeResponse
{
    public function getMeetingsFakeList()
    {
        return json_encode((object)[
            'items' => [
                (object)[
                    'id' => 'fake_id',
                    'meetingNumber' => 'fake_meetingNumber',
                    'title' => 'fake_title',
                    'password' => 'fake_password',
                    'phoneAndVideoSystemPassword' => 'fake_phoneAndVideoSystemPassword',
                    'meetingType' => 'fake_meetingType',
                    'state' => 'fake_state',
                    'timezone' => 'fake_timezone',
                    'start' => 'fake_start',
                    'end' => 'fake_end',
                    'hostUserId' => 'fake_hostUserId',
                    'hostDisplayName' => 'fake_hostDisplayName',
                    'hostEmail' => 'fake_hostEmail',
                    'hostKey' => 'fake_hostKey',
                    'siteUrl' => 'fake_siteUrl',
                    'webLink' => 'fake_webLink',
                    'sipAddress' => 'fake_sipAddress',
                    'dialInIpAddress' => 'fake_dialInIpAddress',
                    'enabledAutoRecordMeeting' => false,
                    'allowAuthenticatedDevices' => false,
                    'enabledJoinBeforeHost' => true,
                    'joinBeforeHostMinutes' => 5,
                    'enableConnectAudioBeforeHost' => true,
                    'excludePassword' => false,
                    'publicMeeting' => false,
                    'reminderTime' => 15,
                    'enableAutomaticLock' => false,
                    'sessionTypeId' => 600,
                    'scheduledType' => 'fake_scheduledType',
                ],
                (object)[
                    'id' => 'second_fake_id',
                    'meetingNumber' => 'second_fake_meetingNumber',
                    'title' => 'second_fake_title',
                    'password' => 'second_fake_password',
                    'phoneAndVideoSystemPassword' => 'second_fake_phoneAndVideoSystemPassword',
                    'meetingType' => 'second_fake_meetingType',
                    'state' => 'second_fake_state',
                    'timezone' => 'second_fake_timezone',
                    'start' => 'second_fake_start',
                    'end' => 'second_fake_end',
                    'hostUserId' => 'second_fake_hostUserId',
                    'hostDisplayName' => 'second_fake_hostDisplayName',
                    'hostEmail' => 'second_fake_hostEmail',
                    'hostKey' => 'second_fake_hostKey',
                    'siteUrl' => 'second_fake_siteUrl',
                    'webLink' => 'second_fake_webLink',
                    'sipAddress' => 'second_fake_sipAddress',
                    'dialInIpAddress' => 'second_fake_dialInIpAddress',
                    'enabledAutoRecordMeeting' => true,
                    'allowAuthenticatedDevices' => true,
                    'enabledJoinBeforeHost' => false,
                    'joinBeforeHostMinutes' => 10,
                    'enableConnectAudioBeforeHost' => false,
                    'excludePassword' => true,
                    'publicMeeting' => true,
                    'reminderTime' => 30,
                    'enableAutomaticLock' => true,
                    'sessionTypeId' => 601,
                    'scheduledType' => 'second_fake_scheduledType',
                ],
            ],
        ]);
    }
}
