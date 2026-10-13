<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class DeviceConfiguration extends AbstractEntity
{
    public mixed $id = null;

    public ?string $deviceId = null;

    public mixed $configKey = null;

    public mixed $value = null;

    public mixed $created = null;

    public mixed $updated = null;
}
