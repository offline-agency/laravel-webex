<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Membership extends AbstractEntity
{
    public mixed $id = null;

    public mixed $roomId = null;

    public mixed $personId = null;

    public mixed $personEmail = null;

    public mixed $personDisplayName = null;

    public mixed $isModerator = null;

    public mixed $isMonitor = null;

    public mixed $created = null;

    public mixed $isRoomHidden = null;

    public mixed $type = null;
}
