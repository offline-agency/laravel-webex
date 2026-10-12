<?php

namespace Offlineagency\LaravelWebex\Entities\Devices;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class WorkspaceMetrics extends AbstractEntity
{
    public mixed $id = null;

    public mixed $workspaceId = null;

    public mixed $metrics = null;

    public mixed $created = null;
}
