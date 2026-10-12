<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Room extends AbstractEntity
{
    public mixed $id = null;

    public mixed $title = null;

    public mixed $type = null;

    public mixed $isLocked = null;

    public mixed $teamId = null;

    public mixed $lastActivity = null;

    public mixed $creatorId = null;

    public mixed $created = null;

    public mixed $ownerId = null;

    public mixed $classificationId = null;

    public mixed $isAnnouncementOnly = null;

    public mixed $isModerated = null;

    public mixed $isReadOnly = null;

    public mixed $description = null;
}
