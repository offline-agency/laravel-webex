<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Team extends AbstractEntity
{
    public $id;

    public $name;

    public $creatorId;

    public $created;

    public $lastActivity;
}
