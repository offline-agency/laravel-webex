<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class WorkspaceMetrics extends AbstractEntity
{
    public $id;

    public $workspaceId;

    public $metrics;

    public $created;
}
