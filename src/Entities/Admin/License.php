<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class License extends AbstractEntity
{
    public mixed $id = null;

    public mixed $name = null;

    public mixed $totalUnits = null;

    public mixed $consumedUnits = null;

    public mixed $provisionedUnits = null;
}
