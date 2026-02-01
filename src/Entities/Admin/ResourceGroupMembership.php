<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class ResourceGroupMembership extends AbstractEntity
{
    public $id;

    public $resourceGroupId;

    public $personId;

    public $personOrgId;

    public $created;
}
