<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Role extends AbstractEntity
{
    public ?string $id = null;

    public ?string $name = null;

    public mixed $description = null;
}
