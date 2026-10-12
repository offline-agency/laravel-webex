<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Workspace extends AbstractEntity
{
    public mixed $id = null;

    public mixed $name = null;

    public mixed $locationId = null;

    public mixed $orgId = null;

    public mixed $floorId = null;

    public mixed $capacity = null;

    public mixed $displayName = null;

    public mixed $created = null;

    public mixed $updated = null;
}
