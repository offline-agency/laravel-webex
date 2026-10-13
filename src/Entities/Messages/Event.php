<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Event extends AbstractEntity
{
    public ?string $id = null;

    public ?string $resource = null;

    public ?string $type = null;

    public ?string $appId = null;

    public ?string $actorId = null;

    public mixed $data = null;

    public ?string $created = null;
}
