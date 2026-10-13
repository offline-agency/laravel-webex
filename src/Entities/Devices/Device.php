<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Device extends AbstractEntity
{
    public ?string $id = null;

    public ?string $displayName = null;

    public mixed $deviceName = null;

    public mixed $model = null;

    public ?string $serial = null;

    public mixed $organizationId = null;

    public ?string $placeId = null;

    public ?string $created = null;

    public mixed $updated = null;

    public ?string $connectionStatus = null;
}
