<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class WorkspaceMetrics extends AbstractEntity
{
    public mixed $id = null;

    public mixed $name = null;

    public mixed $metrics = null;

    public mixed $created = null;
}
