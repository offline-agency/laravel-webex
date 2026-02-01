<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Workspace extends AbstractEntity
{
    public $id;

    public $name;

    public $locationId;

    public $orgId;

    public $floorId;

    public $capacity;

    public $displayName;

    public $created;

    public $updated;
}
