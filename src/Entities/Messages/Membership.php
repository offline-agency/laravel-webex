<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Membership extends AbstractEntity
{
    public ?string $id = null;

    public ?string $roomId = null;

    public ?string $personId = null;

    public ?string $personEmail = null;

    public ?string $personDisplayName = null;

    public ?bool $isModerator = null;

    public ?bool $isMonitor = null;

    public ?string $created = null;

    public ?bool $isRoomHidden = null;

    public mixed $type = null;
}
