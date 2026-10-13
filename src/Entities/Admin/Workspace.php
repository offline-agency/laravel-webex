<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Workspace extends AbstractEntity
{
    public ?string $id = null;

    public mixed $name = null;

    public ?string $locationId = null;

    public ?string $orgId = null;

    public ?string $floorId = null;

    public int|float|null $capacity = null;

    public ?string $displayName = null;

    public ?string $created = null;

    public mixed $updated = null;
}
