<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class RoomTab extends AbstractEntity
{
    public $id;

    public $roomId;

    public $contentUrl;

    public $displayName;

    public $created;

    public $updated;
}
