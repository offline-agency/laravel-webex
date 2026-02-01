<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class WorkspaceLocation extends AbstractEntity
{
    public $id;

    public $displayName;

    public $address;

    public $capacity;

    public $timezone;

    public $organizationId;

    public $created;

    public $updated;
}
