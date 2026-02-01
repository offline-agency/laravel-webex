<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Device extends AbstractEntity
{
    public $id;

    public $displayName;

    public $deviceName;

    public $model;

    public $serial;

    public $organizationId;

    public $placeId;

    public $created;

    public $updated;

    public $connectionStatus;
}
