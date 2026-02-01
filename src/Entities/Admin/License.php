<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class License extends AbstractEntity
{
    public $id;

    public $name;

    public $totalUnits;

    public $consumedUnits;

    public $provisionedUnits;
}
