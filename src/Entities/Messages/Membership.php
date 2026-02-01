<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Membership extends AbstractEntity
{
    public $id;

    public $roomId;

    public $personId;

    public $personEmail;

    public $personDisplayName;

    public $isModerator;

    public $isMonitor;

    public $created;

    public $isRoomHidden;

    public $type;
}
