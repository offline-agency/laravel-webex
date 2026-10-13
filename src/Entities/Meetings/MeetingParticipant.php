<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class MeetingParticipant extends AbstractEntity
{
    public ?string $id = null;

    public mixed $meetingNumber = null;

    public mixed $title = null;

    public mixed $password = null;

    public mixed $phoneAndVideoSystemPassword = null;

    public mixed $meetingType = null;

    public ?string $state = null;

    public mixed $timezone = null;

    public mixed $start = null;

    public mixed $end = null;

    public mixed $hostUserId = null;

    public mixed $hostDisplayName = null;

    public ?string $hostEmail = null;

    public mixed $hostKey = null;

    public ?string $siteUrl = null;

    public mixed $webLink = null;

    public mixed $sipAddress = null;

    public mixed $dialInIpAddress = null;

    public mixed $enabledAutoRecordMeeting = null;

    public mixed $allowAuthenticatedDevices = null;

    public mixed $enabledJoinBeforeHost = null;

    public mixed $joinBeforeHostMinutes = null;

    public mixed $enableConnectAudioBeforeHost = null;

    public mixed $excludePassword = null;

    public mixed $publicMeeting = null;

    public mixed $enableAutomaticLock = null;

    public mixed $sessionTypeId = null;

    public mixed $scheduledType = null;
}
