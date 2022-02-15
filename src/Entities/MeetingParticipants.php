<?php

namespace Offlineagency\LaravelWebex\Entities;

class MeetingParticipants extends AbstractEntity
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
}
