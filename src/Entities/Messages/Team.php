<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Team extends AbstractEntity
{
    public ?string $id = null;

    public ?string $name = null;

    public mixed $creatorId = null;

    public ?string $created = null;

    public mixed $lastActivity = null;
}
