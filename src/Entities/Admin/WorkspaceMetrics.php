<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class WorkspaceMetrics extends AbstractEntity
{
    public $id;

    public $name;

    public $metrics;

    public $created;
}
