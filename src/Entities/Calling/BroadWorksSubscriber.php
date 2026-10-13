<?php

namespace Offlineagency\LaravelWebex\Entities\Calling;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class BroadWorksSubscriber extends AbstractEntity
{
    public ?string $id = null;

    public ?string $userId = null;

    public mixed $enterpriseId = null;

    public mixed $primaryNumber = null;

    public ?string $created = null;

    public mixed $updated = null;
}
