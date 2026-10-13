<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Room extends AbstractEntity
{
    public ?string $id = null;

    public ?string $title = null;

    public ?string $type = null;

    public ?bool $isLocked = null;

    public ?string $teamId = null;

    public ?string $lastActivity = null;

    public ?string $creatorId = null;

    public ?string $created = null;

    public ?string $ownerId = null;

    public ?string $classificationId = null;

    public ?bool $isAnnouncementOnly = null;

    public mixed $isModerated = null;

    public ?bool $isReadOnly = null;

    public ?string $description = null;
}
