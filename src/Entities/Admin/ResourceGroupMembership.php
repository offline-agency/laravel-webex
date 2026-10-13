<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class ResourceGroupMembership extends AbstractEntity
{
    public ?string $id = null;

    public ?string $resourceGroupId = null;

    public ?string $personId = null;

    public ?string $personOrgId = null;

    public mixed $created = null;
}
