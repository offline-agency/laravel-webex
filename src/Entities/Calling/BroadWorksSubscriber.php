<?php

namespace Offlineagency\LaravelWebex\Entities\Calling;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class BroadWorksSubscriber extends AbstractEntity
{
    public $id;

    public $userId;

    public $enterpriseId;

    public $primaryNumber;

    public $created;

    public $updated;
}
