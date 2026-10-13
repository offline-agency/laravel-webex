<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class SpaceClassification extends AbstractEntity
{
    public ?string $id = null;

    public mixed $name = null;

    public ?string $description = null;

    public mixed $created = null;

    public mixed $updated = null;
}
