<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Event extends AbstractEntity
{
    public mixed $id = null;

    public mixed $resource = null;

    public mixed $type = null;

    public mixed $appId = null;

    public mixed $actorId = null;

    public mixed $data = null;

    public mixed $created = null;
}
