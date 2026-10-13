<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class WorkspaceLocation extends AbstractEntity
{
    public ?string $id = null;

    public mixed $name = null;

    public ?string $address = null;

    public mixed $capacity = null;

    public mixed $timezone = null;

    public mixed $orgId = null;

    public mixed $created = null;

    public mixed $updated = null;
}
