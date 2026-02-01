<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Location extends AbstractEntity
{
    public $id;

    public $name;

    public $address;

    public $capacity;

    public $timezone;

    public $orgId;

    public $created;

    public $updated;
}
