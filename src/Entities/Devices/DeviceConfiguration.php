<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class DeviceConfiguration extends AbstractEntity
{
    public $id;

    public $deviceId;

    public $configKey;

    public $value;

    public $created;

    public $updated;
}
