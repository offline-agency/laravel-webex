<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Webhooks extends AbstractEntity
{
    public ?string $id = null;

    public ?string $name = null;

    public ?string $targetUrl = null;

    public ?string $resource = null;

    public ?string $event = null;

    public ?string $filter = null;

    public ?string $secret = null;

    public ?string $status = null;

    public ?string $created = null;

    public ?string $ownedBy = null;
}
