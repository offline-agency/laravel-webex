<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class WorkspaceLocation extends AbstractEntity
{
    public ?string $id = null;

    public ?string $displayName = null;

    public ?string $address = null;

    public mixed $capacity = null;

    public mixed $timezone = null;

    public mixed $organizationId = null;

    public mixed $created = null;

    public mixed $updated = null;
}
