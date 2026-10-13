<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class RoomTab extends AbstractEntity
{
    public ?string $id = null;

    public ?string $roomId = null;

    public ?string $contentUrl = null;

    public ?string $displayName = null;

    public ?string $created = null;

    public mixed $updated = null;
}
