<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Organization extends AbstractEntity
{
    public $id;

    public $displayName;

    public $name;

    public $created;

    public $orgPreferences;

    public $licenses;

    public $preferredGeo;
}
