<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class HybridConnector extends AbstractEntity
{
    public ?string $id = null;

    public mixed $name = null;

    public mixed $clusterId = null;

    public ?string $created = null;

    public mixed $updated = null;
}
