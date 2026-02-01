<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Webhooks extends AbstractEntity
{
    public $id;

    public $name;

    public $targetUrl;

    public $resource;

    public $event;

    public $filter;

    public $secret;

    public $status;

    public $created;

    public $ownedBy;
}
