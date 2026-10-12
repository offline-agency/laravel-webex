<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Workspace extends AbstractEntity
{
    public mixed $id = null;

    public mixed $displayName = null;

    public mixed $placeId = null;

    public mixed $locationId = null;

    public mixed $floorId = null;

    public mixed $capacity = null;

    public mixed $organizationId = null;

    public mixed $created = null;

    public mixed $updated = null;
}
