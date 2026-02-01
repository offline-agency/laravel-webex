<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Workspace extends AbstractEntity
{
    public $id;

    public $displayName;

    public $placeId;

    public $locationId;

    public $floorId;

    public $capacity;

    public $organizationId;

    public $created;

    public $updated;
}
