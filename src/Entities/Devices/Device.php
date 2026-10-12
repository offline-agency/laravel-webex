<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Device extends AbstractEntity
{
    public mixed $id = null;

    public mixed $displayName = null;

    public mixed $deviceName = null;

    public mixed $model = null;

    public mixed $serial = null;

    public mixed $organizationId = null;

    public mixed $placeId = null;

    public mixed $created = null;

    public mixed $updated = null;

    public mixed $connectionStatus = null;
}
