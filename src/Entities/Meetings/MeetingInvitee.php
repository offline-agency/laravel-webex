<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class MeetingInvitee extends AbstractEntity
{
    public mixed $id = null;

    public mixed $email = null;

    public mixed $displayName = null;

    public mixed $coHost = null;

    public mixed $meetingId = null;

    public mixed $panelist = null;
}
