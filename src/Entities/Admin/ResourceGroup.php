<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class ResourceGroup extends AbstractEntity
{
    public $id;

    public $name;

    public $orgId;

    public $created;

    public $updated;
}
