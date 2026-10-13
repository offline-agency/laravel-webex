<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class ResourceGroup extends AbstractEntity
{
    public ?string $id = null;

    public ?string $name = null;

    public ?string $orgId = null;

    public mixed $created = null;

    public mixed $updated = null;
}
