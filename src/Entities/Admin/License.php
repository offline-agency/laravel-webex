<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class License extends AbstractEntity
{
    public ?string $id = null;

    public ?string $name = null;

    public int|float|null $totalUnits = null;

    public int|float|null $consumedUnits = null;

    public mixed $provisionedUnits = null;
}
