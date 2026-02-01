<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Event extends AbstractEntity
{
    public $id;

    public $resource;

    public $type;

    public $appId;

    public $actorId;

    public $data;

    public $created;
}
