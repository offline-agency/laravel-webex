<?php

namespace Offlineagency\LaravelWebex\Entities\Admin;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class AdminAuditEvent extends AbstractEntity
{
    public $id;

    public $actorId;

    public $actorOrgId;

    public $application;

    public $event;

    public $orgId;

    public $created;

    public $targets;

    public $context;
}
