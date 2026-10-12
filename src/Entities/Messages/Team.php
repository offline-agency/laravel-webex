<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Team extends AbstractEntity
{
    public mixed $id = null;

    public mixed $name = null;

    public mixed $creatorId = null;

    public mixed $created = null;

    public mixed $lastActivity = null;
}
