<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Webhooks extends AbstractEntity
{
    public mixed $id = null;

    public mixed $name = null;

    public mixed $targetUrl = null;

    public mixed $resource = null;

    public mixed $event = null;

    public mixed $filter = null;

    public mixed $secret = null;

    public mixed $status = null;

    public mixed $created = null;

    public mixed $ownedBy = null;
}
