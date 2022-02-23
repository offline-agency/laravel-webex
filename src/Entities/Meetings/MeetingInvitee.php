<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class MeetingInvitee extends AbstractEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $displayName;

    /**
     * @var bool
     */
    public $coHost;

    /**
     * @var string
     */
    public $meetingId;

    /**
     * @var bool
     */
    public $panelist;
}
