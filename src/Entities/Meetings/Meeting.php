<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Meeting extends AbstractEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $meetingNumber;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $phoneAndVideoSystemPassword;

    /**
     * @var string
     */
    public $meetingType;

    /**
     * @var string
     */
    public $state;

    /**
     * @var string
     */
    public $timezone;

    /**
     * @var string
     */
    public $start;

    /**
     * @var string
     */
    public $end;

    /**
     * @var string
     */
    public $hostUserId;

    /**
     * @var string
     */
    public $hostDisplayName;

    /**
     * @var string
     */
    public $hostEmail;

    /**
     * @var string
     */
    public $hostKey;

    /**
     * @var string
     */
    public $siteUrl;

    /**
     * @var string
     */
    public $webLink;

    /**
     * @var string
     */
    public $sipAddress;

    /**
     * @var string
     */
    public $dialInIpAddress;

    /**
     * @var bool
     */
    public $enabledAutoRecordMeeting;

    /**
     * @var bool
     */
    public $allowAuthenticatedDevices;

    /**
     * @var bool
     */
    public $enabledJoinBeforeHost;

    /**
     * @var int
     */
    public $joinBeforeHostMinutes;

    /**
     * @var bool
     */
    public $enableConnectAudioBeforeHost;

    /**
     * @var bool
     */
    public $excludePassword;

    /**
     * @var bool
     */
    public $publicMeeting;

    /**
     * @var bool
     */
    public $enableAutomaticLock;

    /**
     * @var int
     */
    public $sessionTypeId;

    /**
     * @var string
     */
    public $scheduledType;

    /**
     * @var string
     */
    public $agenda;

    /**
     * @var string
     */
    public $recurrence;

    /**
     * @var string
     */
    public $roomId;

    /**
     * @var bool
     */
    public $allowAnyUserToBeCoHost;

    /**
     * @var int
     */
    public $reminderTime;

    /**
     * @var bool
     */
    public $enabledWebcastView;

    /**
     * @var string
     */
    public $panelistPassword;

    /**
     * @var string
     */
    public $phoneAndVideoSystemPanelistPassword;

    /**
     * @var int
     */
    public $automaticLockMinutes;

    /**
     * @var bool
     */
    public $allowFirstUserToBeCoHost;

    /**
     * @var object
     */
    public $telephony;

    /**
     * @var object
     */
    public $registration;

    /**
     * @var array
     */
    public $integrationTags;
}
