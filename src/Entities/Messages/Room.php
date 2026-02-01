<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Room extends AbstractEntity
{
    public $id;

    public $title;

    public $type;

    public $isLocked;

    public $teamId;

    public $lastActivity;

    public $creatorId;

    public $created;

    public $ownerId;

    public $classificationId;

    public $isAnnouncementOnly;

    public $isModerated;

    public $isReadOnly;

    public $description;
}
