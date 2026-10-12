<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class ResourceGroupMembership extends AbstractEntity
{
    public mixed $id = null;

    public mixed $resourceGroupId = null;

    public mixed $personId = null;

    public mixed $personOrgId = null;

    public mixed $created = null;
}
