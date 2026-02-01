<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class HybridCluster extends AbstractEntity
{
    public $id;

    public $name;

    public $edgeFqdn;

    public $created;

    public $updated;
}
