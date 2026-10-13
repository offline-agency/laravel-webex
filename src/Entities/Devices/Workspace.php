<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Workspace extends AbstractEntity
{
    public ?string $id = null;

    public ?string $displayName = null;

    public mixed $placeId = null;

    public ?string $locationId = null;

    public ?string $floorId = null;

    public int|float|null $capacity = null;

    public mixed $organizationId = null;

    public ?string $created = null;

    public mixed $updated = null;
}
